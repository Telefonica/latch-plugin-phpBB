<?php

/*
  Latch phpBB3 plugin - Integrates Latch into the phpMyAdmin authentication process.
  Copyright (C) 2013 Eleven Paths

  This library is free software; you can redistribute it and/or
  modify it under the terms of the GNU Lesser General Public
  License as published by the Free Software Foundation; either
  version 2.1 of the License, or (at your option) any later version.

  This library is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
  Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public
  License along with this library; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (!defined('IN_PHPBB')) {
    exit;
}

function login_latch(&$username, &$password) {
    global $phpbb_root_path, $phpEx, $config;

    session_start();
    @include_once ($phpbb_root_path . "latch/LatchWrapper.php");

    $username = strtolower($username);

    $method = $config['latch_auth_target'];
    $include = ($phpbb_root_path . 'includes/auth/auth_' . $method . "." . $phpEx);
    include_once($include);


    $login = 'login_' . $method;

    $login_fail = array(
        'status' => LOGIN_ERROR_PASSWORD,
        'error_msg' => 'LOGIN_ERROR_PASSWORD',
        'user_row' => array('user_id' => ANONYMOUS),
    );

    $login_result = $login_fail;

    if (function_exists($login)) {
        $login_result = $login($username, $password);
    }

    if ($login_result ['status'] != LOGIN_SUCCESS || isset($_REQUEST['latch'])) {
        return $login_result;
    }

    if (isset($_REQUEST['otp']) && isset($_SESSION['token'])) {
        if ($_REQUEST['otp'] == $_SESSION['token']) {
            return $login_result;
        } else {
            return $login_fail;
        }
    }
    $accountId = getAccountIdFromStorage($username);

    if ($accountId) {
        $status = getLatchStatus($accountId);

        if ($status != null) {
            if ($status['accountBlocked']) {
                return $login_fail;
            } else if (isset($status['twoFactor'])) {
                $_SESSION['token'] = $status['twoFactor'];
                include_once($phpbb_root_path . "latch/secondFactorForm.php");
                die();
            }
        }
    }

    return $login_result;
}

function acp_latch(&$new) {
    global $phpbb_root_path, $phpEx, $config;

    include_once ($phpbb_root_path . "latch/LatchPersistence.php");

    if (isset($_REQUEST['latch_auth_target']) && ctype_alnum($_REQUEST['latch_auth_target'])) {
        setAuthTarget($_REQUEST['latch_auth_target']);
    }

    $auth_target_latch = $config['latch_auth_target'];

    if (isset($_REQUEST['latch_appId']) && isset($_REQUEST['latch_appSecret'])) {

        $appId = $_REQUEST['latch_appId'];
        $appSecret = $_REQUEST['latch_appSecret'];

        if (ctype_alnum($appId) && strlen($appId) == 20 && ctype_alnum($appSecret) && strlen($appSecret) == 40) {
            setApplicationConfig($appId, $appSecret);
        }
    }

    $auth_plugins = array();

    $dp = opendir($phpbb_root_path . 'includes/auth');

    if ($dp) {
        while (($file = readdir($dp) ) !== false) {
            if (preg_match('#^auth_(.*?)\.' . $phpEx . '$#', $file)) {
                $auth_plugins[] = basename(preg_replace('#^auth_(.*?)\.' . $phpEx . '$#', '\1', $file));
            }
        }
        closedir($dp);

        sort($auth_plugins);
    }
    $tpl = '
    <dl>
        <dt><label for = "latch_auth_target"> Latch - Authentication Method:</label><br /><span>Authentication method that Latch will use to authenticate to. </span></dt>
        <dd><select id = "latch_auth_target" name = "latch_auth_target">';

    foreach ($auth_plugins as $plugin) {
        if ($plugin != "latch") {
            $plugin = ucfirst($plugin) != null ? ucfirst($plugin) : "db";
            $selected = "";
            if (strtolower($plugin) == strtolower($auth_target_latch)) {
                $selected = "selected";
            }
            $tpl .= "<option value = '$plugin' $selected>$plugin</option>";
        }
    }

    $tpl.= '</select>   
        </dd>
    </dl>
    <dl>
        <dt><label for = "latch_appId"> Latch - ApplicationID:</label><br />
            <span>Application ID obtained from Latch\'s web. DON\'T change once set. </span>
        </dt>
        <dd><input type="text" id="latch_appId" name="latch_appId" required pattern="[a-zA-Z0-9]{20}" size=40 value="' . htmlentities($new['application_id']) . '"></dd>
    </dl>
    <dl>
        <dt><label for = "latch_appSecret"> Latch - ApplicationSecret:</label><br />
            <span>Application Secret obtained from Latch\'s web. DON\'T change once set.</span>
        </dt>
        <dd><input type="text" id="latch_appSecret" pattern="[a-zA-Z0-9]{40}" required name="latch_appSecret" size=40 value="' . htmlentities($new['application_secret']) . '"></dd>
    </dl>
    ';

// These are fields required in the config table
    return array(
        'tpl' => $tpl,
        'config' => array('auth_target')
    );
}

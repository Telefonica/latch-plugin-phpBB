<?php

/*
  Latch phpBB3 plugin - Integrates Latch into the phpBB3 authentication process.
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

function setApplicationConfig($appId, $appSecret) {
    global $db, $config;
    if ($config['application_id'] != null && $config['application_secret'] != null) {

        $sql_array = array(
            'config_value' => $appId,
        );

        $sql = 'UPDATE ' . CONFIG_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_array) . ' WHERE config_name = \'application_id\';';

        $db->sql_query($sql);

        $sql_array = array(
            'config_value' => $appSecret,
        );
        $sql = 'UPDATE ' . CONFIG_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_array) . ' WHERE config_name = \'application_secret\';';

        $db->sql_query($sql);
    } else {

        $sql_array = array(
            'config_name' => 'application_id',
            'config_value' => $_POST['latch_appId'],
            'is_dynamic' => 0
        );

        $sql = 'INSERT INTO ' . CONFIG_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array);

        $db->sql_query($sql);

        $sql_array = array(
            'config_name' => 'application_secret',
            'config_value' => $_POST['latch_appSecret'],
            'is_dynamic' => 0
        );

        $sql = 'INSERT INTO ' . CONFIG_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array);

        $db->sql_query($sql);
    }
}

function setAuthTarget($target) {
    global $db, $config;
    if (ctype_alnum($target)) {
        if ($config['latch_auth_target'] != null) {

            $sql_array = array(
                'config_value' => $target,
            );

            $sql = 'UPDATE ' . CONFIG_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_array) . ' WHERE config_name = \'latch_auth_target\';';

            $db->sql_query($sql);
        } else {
            $sql_array = array(
                'config_name' => 'latch_auth_target',
                'config_value' => $target,
                'is_dynamic' => 0
            );

            $sql = 'INSERT INTO ' . CONFIG_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_array);

            $db->sql_query($sql);
        }
    }

    $create_latch_table = "CREATE TABLE IF NOT EXISTS latch_accounts (username VARCHAR(20),account_id VARCHAR(64));";

    $db->sql_query($create_latch_table);
}

function pairUser($username, $account_id) {
    global $db;

    $sql_array = array(
        'username' => $username,
        'account_id' => $account_id
    );

    $sql = 'INSERT INTO latch_accounts ' . $db->sql_build_array('INSERT', $sql_array);

    $db->sql_query($sql);
}

function unpairUser($username) {
    global $db;

    $unpair_user = "DELETE FROM latch_accounts WHERE username = '" . $db->sql_escape($username) . "';";
    $db->sql_query($unpair_user);
}

function getAccountIdFromStorage($username) {
    global $db;
    $sql_array = array(
        'SELECT' => 'account_id',
        'FROM' => array(
            LATCH_ACCOUNTS => 'latch_accounts'
        ),
        'WHERE' => 'username = \'' . $username . '\';'
    );

    $result = $db->sql_query($db->sql_build_query('SELECT', $sql_array));

    $row = $db->sql_fetchrow($result);

    $db->sql_freeresult($result);

    return $row['account_id'];
}

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
include_once ($phpbb_root_path . "latch/LatchWrapper.php");

class ucp_latch {

    var $p_master;
    var $paired;
    var $username;
    var $latch_enabled;

    function ucp_latch(&$p_master) {
        $this->manage_pairing();
        $this->assign_template_variables();
    }

    function main($id, $mode) {
        global $template, $phpbb_root_path, $config;
        $this->page_title = 'LATCH';
        $this->tpl_name = 'ucp_latch';
        $template->assign_vars(array(
            'CURRENT_USERNAME' => $this->username,
            'ACCOUNT_ID' => $this->account_id,
            'PHPBB_ROOT' => $phpbb_root_path,
            'AUTH_METHOD' => $config['auth_method'],
        ));
    }

    public function manage_pairing() {
        if (isset($_POST ['operation']) && isset($_POST['user'])) {
            if (ctype_alnum($_POST['user'])) {
                if (isset($_POST ['pairingToken'])) {
                    if (ctype_alnum($_POST['pairingToken'])) {
                        pairLatchAccount($_POST ['pairingToken'], $_POST['user']);
                    }
                } else if ($_POST ['operation'] == "unpair") {
                    unpairLatchAccount($_POST['user']);
                }
            }
        }
    }

    public function assign_template_variables() {
        global $user, $p_master;
        $this->p_master = &$p_master;
        $this->username = $user->data['username'];
        $this->latch_enabled = "";
        $this->account_id = getAccountIdFromStorage($this->username);
        if ($this->account_id == null) {
            $this->account_id = "unpaired";
        }
    }

}

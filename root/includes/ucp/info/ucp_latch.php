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

class ucp_latch_info {

    function module() {
        return array(
            'filename' => 'ucp_latch',
            'title' => 'Latch Pairing',
            'version' => '1.0.0',
            'modes' => array(
                'index' => array('title' => 'Latch Configuration', 'auth' => '', 'cat' => array('')),
            ),
        );
    }

    function install() {
        
    }

    function uninstall() {
        
    }

}

?>
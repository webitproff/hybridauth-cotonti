<?php
/* ====================
[BEGIN_COT_EXT]
Code=hybridauth
Name=HybridAuth
Description=social networks for authentication
Version=3.1-3.13.0
Date=Jul 25, 2026
Copyright=(c) 2026 webitproff https://github.com/webitproff/hybridauth-cotonti/
Notes=
Auth_guests=RW
Lock_guests=2345A
Auth_members=RW
Lock_members=2345
Requires_modules=users
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
autoreg=01:radio::0:Auto-register with social networks
block_manual_registration=02:radio::0:Block built-in registration form
[END_COT_EXT_CONFIG]
==================== */
defined('COT_CODE') or die('Wrong URL');


/**
 * hybridauth.setup.php - Register data in $db_core and $db_config. Setup & Config File for the Plugin HybridAuth
 * Filename: hybridauth.setup.php
 *
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * ReadMeMore:       https://abuyfile.com/market/cotonti/plugs/hybridauth
 * Support:          https://abuyfile.com/forums/cotonti/custom/plugs/hybridauth
 * Source:           https://github.com/webitproff/hybridauth-cotonti
 * 
 * Date: Jul 25, 2026
 * @package hybridauth
 * @version 3.1-3.13.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/hybridauth-cotonti
 * @license BSD
 */

<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.auth.tags
[END_COT_EXT]
==================== */

/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4 
 *
 * подключиемся к хуку users.auth.tags в штатном файле login.php 
 * В нем шаблон $templateFile = cot_tplfile('login', 'core');
 * В объект $t = new XTemplate($templateFile) выводим наши сообщения через штатную cot_display_messages($t) 
 * например, "Пользователь с таким email уже существует..."
 *
 * Filename: plugins/hybridauth/hybridauth.login.tags.php
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

defined('COT_CODE') or die('Wrong URL');

// Выводим системные сообщения (например, "Пользователь с таким email уже существует...")
cot_display_messages($t);
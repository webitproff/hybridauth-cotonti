<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=global
[END_COT_EXT]
==================== */
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4 
 * Хук global. Добавляем плагин в глобальную экосистему нашего движка.
 * Filename: plugins/hybridauth/hybridauth.global.php 
 *
 * Подключаем файл функций plugins/hybridauth/inc/hybridauth.functions.php. 
 * Подключенный файл функций уже должен содержать подключение файлов локализации и hybridauth.config.php
 *
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
require_once cot_incfile('hybridauth', 'plug');
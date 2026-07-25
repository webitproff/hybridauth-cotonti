<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.logout
[END_COT_EXT]
==================== */
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4 
 *
 * подключаемся к хуку users.logout в штатном файле login.php в момент, 
 * когда пользователь нажимает кнопку «Выйти» и система обрабатывает выход из учётной записи Cotonti 
 * Проверяет сессию. Обращается к адаптерам всех провайдеров, с которыми мог быть связан пользователь
 * Вызывает disconnectAllAdapters() и токен доступа, сохранённый в сессии, удаляется. 
 * хукинг файла hybridauth.logout.php обеспечивает корректный и безопасный выход, 
 * синхронизируя состояние сессии Cotonti с состоянием сессий у социальных провайдеров
 *
 * Filename: plugins/hybridauth/hybridauth.logout.php
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

if (isset($_SESSION['cot_hybridauth'])) {
    require_once cot_incfile('hybridauth', 'plug');
    try {
        $hybridauth = new Hybridauth\Hybridauth($hybridauth_config);
        $hybridauth->disconnectAllAdapters();
    } catch (Exception $e) {}
    unset($_SESSION['cot_hybridauth']);
}
<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.auth.check.query
[END_COT_EXT]
==================== */

/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4 
 *
 * Устанавливает критерии выбора пользователя для аутентификации. 
 * Переопределяем $userSelectCondition и $userSelectParams в штатном файле login.php
 * подключиемся к хуку users.auth.check.query и ставим свои методы аутентификации.
 *
 * Filename: plugins/hybridauth/hybridauth.login.php
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

// Обрабатываем только запросы, пришедшие через соцсеть (с параметром provider)
$socialProvider = cot_import('provider', 'G', 'ALP');
if (!empty($socialProvider) && isset($_SESSION['cot_hybridauth'])) {
    require_once cot_incfile('hybridauth', 'plug');
    global $db, $db_users, $hybridauth_config, $userSelectCondition, $userSelectParams;
    $provider = $_SESSION['cot_hybridauth']['provider'];
    $identifier = $_SESSION['cot_hybridauth']['identifier'];
    $providers = array_keys($hybridauth_config['providers']);
    if (!empty($provider) && in_array(ucfirst($provider), $providers) && !empty($identifier)) {
        $field = "user_{$provider}_id";
        $userSelectCondition = "`$field` = :hybridauth_id";
        $userSelectParams = ['hybridauth_id' => $identifier];
    }
}

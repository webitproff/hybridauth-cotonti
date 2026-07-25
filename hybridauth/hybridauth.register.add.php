<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.add.validate
[END_COT_EXT]
==================== */
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Подключается к хуку users.register.add.validate в штатном процессе регистрации Cotonti.
 * Срабатывает при отправке формы ручной регистрации (до фактического создания пользователя).
 *
 * Выполняет две основные функции:
 * 1. Блокирует ручную регистрацию, если в настройках плагина включён параметр
 *    block_manual_registration. В этом случае регистрация запрещается, а пользователь
 *    видит сообщение о необходимости использовать социальный вход.
 * 2. Если в сессии присутствуют данные от социального провайдера (cot_hybridauth),
 *    и на этот момент нет других ошибок валидации, плагин:
 *    - получает профиль пользователя из соцсети,
 *    - заполняет массив $ruser данными из этого профиля (логин, email, аватар и т.д.),
 *    - принудительно отключает активацию учётной записи (regnoactivation = true),
 *      чтобы пользователь мог сразу войти.
 *
 * Таким образом, даже при выключенной авторегистрации (autoreg = 0) пользователь,
 * пришедший через соцсеть, может завершить ручную регистрацию с уже заполненными полями,
 * а при блокировке ручной регистрации получает соответствующее сообщение.
 *
 * Filename: plugins/hybridauth/hybridauth.register.add.php
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

// Блокировка ручной регистрации через отдельный параметр (не autoreg)
if ($cfg['plugin']['hybridauth']['block_manual_registration']) {
    cot_error('hybridauth_autoreg_inaction');
}

// Если пользователь пришёл через соцсеть и ошибок нет
if (isset($_SESSION['cot_hybridauth']) && !cot_error_found()) {
    require_once cot_incfile('hybridauth', 'plug');

    $provider_code = $_SESSION['cot_hybridauth']['provider'];
    $provider_name = ucfirst($provider_code);

    try {
        // Получаем профиль из соцсети
        $hybridauth = new Hybridauth\Hybridauth($hybridauth_config);
        $adapter = $hybridauth->authenticate($provider_name);
        $user_profile = $adapter->getUserProfile();

        // Заполняем поля регистрации данными из соцсети
        $ruser = hybridauth_complete_profile($user_profile, $ruser, $provider_code, true);

        // Отключаем активацию (пользователь сразу активен)
        $cfg['users']['regnoactivation'] = true;
    } catch (Exception $e) {
        // При ошибке — перенаправляем на страницу входа через соцсеть
        cot_redirect(cot_url('plug', 'e=hybridauth&a=login&provider=' . $provider_name));
    }
}


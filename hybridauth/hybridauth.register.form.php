<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.main
[END_COT_EXT]
==================== */
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Подключается к хуку users.register.main, который срабатывает при выводе страницы
 * ручной регистрации (users/register). Если в сессии присутствуют данные от социального
 * провайдера (cot_hybridauth), это означает, что пользователь пришёл через соцсеть,
 * но по каким-то причинам не был зарегистрирован автоматически (например, выключена
 * авторегистрация или произошла ошибка).
 *
 * В этом случае плагин:
 * - получает профиль пользователя из соцсети,
 * - заполняет массив $ruser данными из этого профиля (логин, email, аватар и т.д.),
 * - предзаполняет поля даты рождения ($rday, $rmonth, $ryear), если они ещё не заданы.
 *
 * Таким образом, пользователю не нужно вводить свои данные заново — форма регистрации
 * уже содержит всю необходимую информацию, полученную от провайдера. Пользователю
 * остаётся только подтвердить регистрацию.
 *
 * Filename: plugins/hybridauth/hybridauth.register.form.php
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
    $provider = ucfirst($_SESSION['cot_hybridauth']['provider']);
    $provider_code = $_SESSION['cot_hybridauth']['provider'];
    try {
        $hybridauth = new Hybridauth\Hybridauth($hybridauth_config);
        $adapter = $hybridauth->authenticate($provider);
        $user_profile = $adapter->getUserProfile();
    } catch (Exception $e) {
        cot_redirect(cot_url('plug', 'e=hybridauth&a=login&provider=' . $provider));
    }
    $ruser = hybridauth_complete_profile($user_profile, $ruser, $provider_code, true);
    if (empty($rmonth) && empty($rday) && empty($ryear)) {
        $rday = $user_profile->birthDay;
        $rmonth = $user_profile->birthMonth;
        $ryear = $user_profile->birthYear;
    }
}
<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.add.done
[END_COT_EXT]
==================== */
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Подключается к хуку users.register.add.done, который срабатывает сразу после успешного
 * завершения ручной регистрации (пользователь уже создан в БД). Если в этот момент в сессии
 * присутствует массив cot_hybridauth (т.е. пользователь пришёл через соцсеть и завершил
 * ручную регистрацию), плагин немедленно перенаправляет браузер на страницу входа с
 * параметрами a=check и системным ключом x, автоматически авторизуя пользователя без
 * необходимости вводить логин и пароль повторно.
 *
 * Это устраняет лишний шаг: после заполнения формы регистрации (с предзаполненными данными
 * из соцсети) пользователь сразу попадает на сайт под своей новой учётной записью.
 *
 * Filename: plugins/hybridauth/hybridauth.register.redirect.php
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
    cot_redirect(cot_url('login', 'a=check&x=' . $sys['xk'], '', true));
}
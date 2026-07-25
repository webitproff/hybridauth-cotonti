<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Основной контроллер (standalone) плагина. Обрабатывает все запросы к /hybridauth:
 * - a=login   – вход через соцсеть (создание нового пользователя при autoreg=1)
 * - a=link    – привязка соцсети к существующему профилю
 * - a=connect – то же, что link (альтернативное действие)
 * - a=unlink  – отвязка соцсети от профиля
 * - callback  – возврат от провайдера (OAuth2 code flow)
 *
 * Также принудительно создаёт папку и файл для debug-лога Hybridauth при включённом debug_mode,
 * чтобы избежать ошибок записи.
 *
 * Использует глобальный конфигурационный массив $hybridauth_config, загруженный в
 * inc/hybridauth.functions.php, и работает в тесной связке с хуками:
 * - hybridauth.login.php (users.auth.check.query)
 * - hybridauth.logout.php (users.logout)
 * - hybridauth.register.add.php, hybridauth.register.form.php (при ручной регистрации)
 *
 * Filename: plugins/hybridauth/hybridauth.php
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
global $hybridauth_config, $db, $db_users, $sys, $cfg, $L, $usr;

$provider = cot_import('provider', 'G', 'ALP');
$a = cot_import('a', 'G', 'ALP');

// ==== Принудительное создание папки и файла debug-лога ====
if (!empty($hybridauth_config['debug_mode']) && !empty($hybridauth_config['debug_file'])) {
    $logDir = dirname($hybridauth_config['debug_file']);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    if (!file_exists($hybridauth_config['debug_file'])) {
        touch($hybridauth_config['debug_file']);
    }
}
try {
    $hybridauth = new Hybridauth\Hybridauth($hybridauth_config);
} catch (Exception $e) {
    cot_die_message(500);
}

// Обработка возврата от провайдера (callback) – работает для login, link, connect
if (!$provider) {
    // Ошибка, переданная провайдером (например, пользователь отказал в доступе)
    if (!empty($_GET['error'])) {
        $errorDescription = $_GET['error_description'] ?? 'Неизвестная ошибка провайдера.';
        cot_error(htmlspecialchars($errorDescription));
        unset($_SESSION['cot_hybridauth']);
        cot_redirect(cot_url('login'));
    }
    if (empty($_GET['code'])) {
        cot_die_message(403);
    }
    if (empty($_SESSION['cot_hybridauth']['provider'])) {
        cot_die_message(403);
    }
    $provider = ucfirst($_SESSION['cot_hybridauth']['provider']);
    if (empty($hybridauth_config['providers'][$provider]['enabled'])) {
        cot_die_message(403);
    }
    try {
        $adapter = $hybridauth->authenticate($provider);
        $user_profile = $adapter->getUserProfile();
    } catch (Exception $e) {
        cot_die_message(500);
    }
    $provider = strtolower($provider);
    // Восстанавливаем действие из сессии (login, link или connect)
    $a = isset($_SESSION['cot_hybridauth']['action']) ? $_SESSION['cot_hybridauth']['action'] : 'login';
    $_SESSION['cot_hybridauth'] = [
        'provider'   => $provider,
        'identifier' => $user_profile->identifier,
    ];
} elseif (empty($hybridauth_config['providers'][$provider]['enabled'])) {
    cot_die_message(403);
}

// Авторизованный пользователь пытается войти через соцсеть – перенаправляем на страницу логина
if ($a == 'login' && $usr['id'] > 0) {
    unset($_SESSION['cot_hybridauth']);
    cot_redirect(cot_url('login', '', '', true));
}

// Гостевой вход
if ($a == 'login' && $usr['id'] == 0) {
    if (!isset($adapter)) {
        $_SESSION['cot_hybridauth']['action'] = 'login';
        $_SESSION['cot_hybridauth']['provider'] = strtolower($provider);
        try {
            $adapter = $hybridauth->authenticate($provider);
            $user_profile = $adapter->getUserProfile();
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $errorMsg = match ($errorCode) {
                0,1,2,3,4,5 => $L['hybridauth_error_' . $errorCode] ?? $L['hybridauth_error_0'],
                6 => $L['hybridauth_error_6'],
                7 => $L['hybridauth_error_7'],
                default => $e->getMessage(),
            };
            cot_error($errorMsg);
            if (isset($adapter)) $adapter->disconnect();
            unset($_SESSION['cot_hybridauth']);  // ← очищаем сессию, чтобы не было редиректа
            cot_redirect(cot_url('users', 'm=register', '', true));
        }
    }
    $provider_code = strtolower($provider);

    // ---------- ПРОВЕРКА СУЩЕСТВОВАНИЯ EMAIL ----------
    if (!empty($user_profile->email)) {
        $emailCheck = $db->query("SELECT user_id FROM $db_users WHERE user_email = ?", $user_profile->email);
        if ($emailCheck->rowCount() > 0) {
            // Пользователь с таким email уже зарегистрирован
            $existingUserId = $emailCheck->fetchColumn();
            // Проверим, привязан ли уже этот конкретный провайдер (на случай, если тот же email, но другой соцсети)
            $field_name = "user_{$provider_code}_id";
            $linkedCheck = $db->query("SELECT 1 FROM $db_users WHERE user_id = ? AND `$field_name` = ?", [$existingUserId, $user_profile->identifier]);
            if ($linkedCheck->rowCount() == 0) {
                // Email есть, провайдер не привязан – предлагаем войти и привязать
                // cot_message(cot_rc('hybridauth_email_exists_login', ['email' => $user_profile->email]));
				cot_message(
					cot_rc('hybridauth_email_exists_login', [
						'email' => $user_profile->email,
						'provider' => $provider_code
					]),
					'warning'
				);
                $hybridauth->disconnectAllAdapters();   // сброс сессий провайдеров
                unset($_SESSION['cot_hybridauth']);
                cot_redirect(cot_url('login', '', '', true));
            }
            // Если всё же привязан – продолжаем обычный вход (ниже)
        }
    }
    // -------------------------------------------------

    $_SESSION['cot_hybridauth'] = [
        'provider'   => $provider_code,
        'identifier' => $user_profile->identifier,
    ];
    $field_name = "user_{$provider_code}_id";
    $res = $db->query("SELECT * FROM $db_users WHERE `$field_name` = ?", $user_profile->identifier);
    if ($res->rowCount() == 1) {
        session_write_close();
        cot_redirect(cot_url('login', 'a=check&x=' . $sys['xk'] . '&provider=' . $provider_code, '', true));
    } elseif ($cfg['plugin']['hybridauth']['autoreg']) {
        $ruser = hybridauth_complete_profile($user_profile, [], $provider_code, true);
        $ruser['user_password'] = cot_unique(12);
        $old = $cfg['users']['regnoactivation'];
        $cfg['users']['regnoactivation'] = true;
        $userid = cot_add_user($ruser);
        $cfg['users']['regnoactivation'] = $old;
        if ($userid) {
            session_write_close();
            cot_redirect(cot_url('login', 'a=check&x=' . $sys['xk'] . '&provider=' . $provider_code, '', true));
        } else {
            unset($_SESSION['cot_hybridauth']);  // ← очищаем сессию, чтобы не было редиректа
            cot_redirect(cot_url('users', 'm=register', '', true));
        }
    } else {
        cot_message(cot_rc('hybridauth_no_linked_account', ['provider' => $provider]));
        unset($_SESSION['cot_hybridauth']);  // ← очищаем сессию перед редиректом на регистрацию
        cot_redirect(cot_url('users', 'm=register', '', true));
    }
} elseif (($a == 'connect' || $a == 'link') && $usr['id'] > 0) {
    // Если профиль ещё не получен из callback, только тогда идём аутентифицироваться
    if (!isset($user_profile)) {
        $_SESSION['cot_hybridauth']['action'] = $a;
        $_SESSION['cot_hybridauth']['provider'] = strtolower($provider);
        try {
            $adapter = $hybridauth->authenticate($provider);
            $user_profile = $adapter->getUserProfile();
        } catch (Exception $e) {
            cot_error($e->getMessage());
            unset($_SESSION['cot_hybridauth']);
            cot_redirect(cot_url('users', 'm=profile', '', true));
        }
    }
    $provider_code = strtolower($provider);
    $field_name = "user_{$provider_code}_id";
    $res = $db->query("SELECT COUNT(*) FROM $db_users WHERE `$field_name` = ?", $user_profile->identifier)->fetchColumn();
    if ($res > 0) {
        cot_error('hybridauth_already_linked');
    } else {
        $db->update($db_users, [
            $field_name                 => $user_profile->identifier,
            "user_{$provider_code}_url" => $user_profile->profileURL,
        ], "user_id = ?", $usr['id']);
    }
    unset($_SESSION['cot_hybridauth']);
    cot_redirect(cot_url('users', 'm=profile', '', true));
} elseif ($a == 'unlink' && $usr['id'] > 0) {
    $provider_code = strtolower($provider);
    $field_name = "user_{$provider_code}_id";
    if (!empty($usr['profile'][$field_name])) {
        $db->update($db_users, [
            $field_name                 => '',
            "user_{$provider_code}_url" => '',
        ], "user_id = ?", $usr['id']);
        try {
            $adapter = $hybridauth->getAdapter($provider);
            $adapter->disconnect();
        } catch (Exception $e) {}
    }
    cot_redirect(cot_url('users', 'm=profile', '', true));
} else {
    cot_die_message(403);
}

<?php
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4 
 *
 * Центральный файл плагина, содержащий все основные функции, конфигурацию
 * и логику работы с социальной аутентификацией. Именно здесь происходит:
 * - подключение штатного автозагрузчика библиотеки HybridAuth 3.x;
 * - инициализация системы логирования (функция hybridauth_log);
 * - загрузка конфигурации провайдеров из hybridauth.config.php в глобальную переменную $hybridauth_config;
 * - заполнение профиля нового пользователя данными из соцсети (hybridauth_complete_profile);
 * - генерация HTML-виджетов: кнопки входа (hybridauth_login) и панели управления привязанными аккаунтами (hybridauth_accounts).
 *
 * Все функции этого файла используются контроллером hybridauth.php, а также
 * хуками hybridauth.login.php, hybridauth.register.*.php и другими частями плагина.
 *
 * Filename: plugins/hybridauth/inc/hybridauth.functions.php
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

// Подключаем необходимые системные файлы Cotonti
require_once cot_incfile('users', 'module');
require_once cot_langfile('hybridauth', 'plug');

// ========================
// АВТОЗАГРУЗКА БИБЛИОТЕКИ
// ========================

// Используем штатный автозагрузчик HybridAuth 3.x
require_once __DIR__ . '/../lib/Hybridauth/autoload.php';

// ========================
// ЛОГИРОВАНИЕ
// ========================

/**
 * Записывает сообщение в лог плагина hybridauth
 *
 * @param string $message Сообщение для записи
 */
function hybridauth_log(string $message): void
{
    global $cfg;
    $logFile = $cfg['plugins_dir'] . '/hybridauth/log/hybridauth.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// ========================
// КОНФИГУРАЦИЯ
// ========================

// Загружаем конфигурационный файл с настройками провайдеров
global $hybridauth_config;
$hybridauth_config = include __DIR__ . '/../conf/hybridauth.config.php';

// ========================
// ФУНКЦИИ ПЛАГИНА
// ========================

/**
 * Заполняет профиль пользователя данными, полученными от провайдера
 *
 * @param object $user_profile  Объект профиля Hybridauth\User\Profile
 * @param array  $ruser         Массив с уже заполненными полями пользователя
 * @param string $provider_code Код провайдера в нижнем регистре (например, 'google')
 * @param bool   $generate_emails Генерировать фиктивный email, если провайдер не передал
 * @return array                Заполненный массив с данными пользователя
 */
function hybridauth_complete_profile($user_profile, $ruser = [], $provider_code = '', $generate_emails = true): array
{
    global $cfg, $cot_extrafields, $db, $db_users;

    // Формируем отображаемое имя, если оно пустое, но есть имя и фамилия
    if (empty($user_profile->displayName) && !empty($user_profile->firstName) && !empty($user_profile->lastName)) {
        $user_profile->displayName = $user_profile->firstName . ' ' . $user_profile->lastName;
    }

    // ---------- ГЕНЕРАЦИЯ ЛОГИНА (УЛУЧШЕННАЯ) ----------
    if (empty($ruser['user_name'])) {
        // Пытаемся взять подходящее значение: displayName или часть email до @
        $rawName = !empty($user_profile->displayName)
            ? $user_profile->displayName
            : (isset($user_profile->email) ? strstr($user_profile->email, '@', true) : '');

        // Удаляем все символы, кроме букв (включая кириллицу), цифр, дефиса и подчёркивания
        $cleanName = preg_replace('/[^\p{L}\p{N}_-]/u', '', $rawName);

        // Если после очистки строка пуста – генерируем запасной вариант
        if (empty($cleanName)) {
            $cleanName = 'user_' . mt_rand(1000, 9999);
        }

        // Обрезаем до максимальной длины поля user_name (100 символов)
        $cleanName = mb_substr($cleanName, 0, 100);

        // Проверяем уникальность и добавляем числовой суффикс при необходимости
        $user_name = $cleanName;
        while ($db->query("SELECT COUNT(*) FROM $db_users WHERE user_name = ?", $user_name)->fetchColumn() > 0) {
            $user_name = $cleanName . mt_rand(2, 9999);
        }
        $ruser['user_name'] = $user_name;
    }

    // ---------- ЭЛЕКТРОННАЯ ПОЧТА ----------
    if (empty($ruser['user_email'])) {
        if (!empty($user_profile->email)) {
            $ruser['user_email'] = $user_profile->email;
        } elseif ($generate_emails) {
            // Создаём фиктивный email на основе ID провайдера и кода провайдера
            $ruser['user_email'] = $user_profile->identifier . '@' . $provider_code . '.com';
            if (mb_strlen($ruser['user_email']) > 64 || !cot_check_email($ruser['user_email'])) {
                $ruser['user_email'] = md5($user_profile->identifier . microtime()) . '@' . $provider_code . '.com';
            }
        }
    }

    // ---------- ДАТА РОЖДЕНИЯ ----------
    if (empty($ruser['user_birthdate'])) {
        $ruser['user_birthdate'] = cot_mktime(1, 0, 0, $user_profile->birthMonth, $user_profile->birthDay, $user_profile->birthYear);
    }

    // ---------- СТРАНА ----------
    if (empty($ruser['user_country']) && strlen($user_profile->country ?? '') == 2) {
        $ruser['user_country'] = $user_profile->country;
    }

    // ---------- ЯЗЫК ----------
    if (empty($ruser['user_lang']) && !$cfg['forcedefaultlang'] && !empty($user_profile->language)) {
        $lang = $user_profile->language;
        if (file_exists("lang/$lang")) {
            $ruser['user_lang'] = $lang;
        }
    }

    // ---------- ПОЛ ----------
    if (empty($ruser['user_gender']) && $user_profile->gender !== null) {
        $ruser['user_gender'] = $user_profile->gender == 'female' ? 'F' : 'M';
    }

    // ---------- АВАТАР ----------
    if ((!empty($user_profile->photoURL) || !empty($user_profile->avatarURL)) && $db->fieldExists($db_users, 'user_avatar')) {
        $ruser['user_avatar'] = empty($user_profile->avatarURL) ? $user_profile->photoURL : $user_profile->avatarURL;
    }

    // ---------- ФОТО ----------
    if (!empty($user_profile->photoURL) && $db->fieldExists($db_users, 'user_photo')) {
        $ruser['user_photo'] = $user_profile->photoURL;
    }

    // ---------- ДОПОЛНИТЕЛЬНЫЕ ПОЛЯ (firstname / first_name) ----------
    if (isset($cot_extrafields[$db_users]['firstname']) && empty($ruser['user_firstname'])) {
        $ruser['user_firstname'] = $user_profile->firstName;
    }
    if (isset($cot_extrafields[$db_users]['first_name']) && empty($ruser['user_first_name'])) {
        $ruser['user_first_name'] = $user_profile->firstName;
    }

    // ---------- ДОПОЛНИТЕЛЬНЫЕ ПОЛЯ (lastname / last_name) ----------
    if (isset($cot_extrafields[$db_users]['lastname']) && empty($ruser['user_lastname'])) {
        $ruser['user_lastname'] = $user_profile->lastName;
    }
    if (isset($cot_extrafields[$db_users]['last_name']) && empty($ruser['user_last_name'])) {
        $ruser['user_last_name'] = $user_profile->lastName;
    }

    // ---------- ID ПРОВАЙДЕРА И ССЫЛКА НА ПРОФИЛЬ ----------
    if (!empty($provider_code)) {
        $ruser["user_{$provider_code}_id"] = $user_profile->identifier;
        $ruser["user_{$provider_code}_url"] = $user_profile->profileURL;
    }

    return $ruser;
}

/**
 * Формирует HTML-код для кнопок входа через соцсети (виджет на странице авторизации)
 *
 * @param string $tpl Имя файла шаблона (без расширения)
 * @return string HTML-код
 */
function hybridauth_login($tpl = 'hybridauth.login'): string
{
    global $hybridauth_config;
    $t = new XTemplate(cot_tplfile($tpl, 'plug'));
    foreach ($hybridauth_config['providers'] as $key => $val) {
        if ($val['enabled']) {
            $t->assign([
                'HYBRID_PROVIDER_NAME' => $key,
                'HYBRID_PROVIDER_CODE' => strtolower($key),
                'HYBRID_PROVIDER_URL'  => cot_url('plug', 'e=hybridauth&a=login&provider=' . $key),
            ]);
            $t->parse('MAIN.HYBRID_PROVIDER');
        }
    }
    $t->parse();
    return $t->text();
}

/**
 * Формирует HTML-блок для управления привязанными аккаунтами в профиле пользователя
 *
 * @param string $tpl Имя файла шаблона
 * @param XTemplate|null $t Экземпляр XTemplate (если уже создан)
 * @return string|bool HTML-код или true, если шаблон уже передан
 */
function hybridauth_accounts($tpl = 'hybridauth.accounts', $t = null)
{
    global $hybridauth_config, $usr, $L;
    $render = false;
    if (!is_object($t)) {
        $t = new XTemplate(cot_tplfile($tpl, 'plug'));
        $render = true;
    }
    foreach ($hybridauth_config['providers'] as $key => $val) {
        if ($val['enabled']) {
            $code = strtolower($key);
            $linked = !empty($usr['profile']["user_{$code}_id"]);
            $action = $linked ? 'unlink' : 'link';
            $t->assign([
                'HYBRID_ACCOUNT_NAME'        => $key,
                'HYBRID_ACCOUNT_CODE'        => $code,
                'HYBRID_ACCOUNT_LINK_URL'    => cot_url('plug', 'e=hybridauth&a=' . $action . '&provider=' . $key),
                'HYBRID_ACCOUNT_LINKED'      => $linked,
                'HYBRID_ACCOUNT_ACTION'      => $L['hybridauth_' . $action],
                'HYBRID_ACCOUNT_PROFILE'     => $linked ? $usr['profile']["user_{$code}_url"] : '',
                'HYBRID_ACCOUNT_CONNECT_URL' => cot_url('plug', 'e=hybridauth&a=connect&provider=' . $key),
                'HYBRID_ACCOUNT_CONNECT'     => $L['hybridauth_connect'],
            ]);
            $t->parse('MAIN.HYBRID_ACCOUNT');
        }
    }
    if ($render) {
        $t->parse();
        return $t->text();
    }
    return true;
}
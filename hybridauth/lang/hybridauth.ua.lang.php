<?php
/**
 * Український мовний файл для плагіна HybridAuth
 *
 * Всі текстові рядки, що використовуються плагіном в інтерфейсі Cotonti:
 * - налаштування в адмін-панелі (cfg_…)
 * - інформаційні повідомлення та назви (info_…)
 * - фронтенд: кнопки входу, повідомлення про помилки, прив’язка/відв’язка акаунтів
 * - сторінка керування полями провайдерів в адмінці (tools)
 *
 * Filename: plugins/hybridauth/lang/hybridauth.ua.lang.php
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

defined('COT_CODE') or die('Wrong URL.');

// ========================
// НАЛАШТУВАННЯ ПЛАГІНА (АДМІНКА)
// ========================
$L['cfg_autoreg'] = 'Увімкнути автореєстрацію через соцмережі';
$L['cfg_autoreg_hint'] = 'Увімкнути автореєстрацію через соцмережі';
$L['cfg_block_manual_registration'] = 'Блокувати стандартну реєстрацію';
$L['cfg_block_manual_registration_hint'] = 'Повністю автоматичний вхід/реєстрація через соцмережі. Звичайна форма реєстрації вимкнена.';

// ========================
// ІНФОРМАЦІЯ ПРО ПЛАГІН (АДМІНКА)
// ========================
$L['info_name'] = 'HybridAuth Авторизація';
$L['info_desc'] = 'Авторизація через Google, Facebook, X/Twitter, OpenID та інші соціальні мережі без використання сторонніх сервісів';
$L['info_notes'] = 'Відредагуйте plugins/hybridauth/conf/hybridauth.config.php після встановлення. Кожного разу після додавання нових провайдерів заходьте в Адміністрування / Конфігурація / HybridAuth та натискайте Оновити.';

// ========================
// ЗАГОЛОВКИ (АДМІНКА, ФРОНТЕНД)
// ========================
$L['hybridauth_title'] = 'HybridAuth Авторизація';
$L['hybridauth'] = 'HybridAuth Авторизація';

// ========================
// ФРОНТЕНД – КНОПКИ ТА ПОВІДОМЛЕННЯ
// ========================
$L['hybridauth_autoreg_inaction'] = 'Стандартна реєстрація вимкнена. Увійдіть за допомогою свого акаунта соціальної мережі або доступних провайдерів.';
$L['hybridauth_signin_with'] = 'Увійти за допомогою';
$L['hybridauth_connect'] = 'Підключити зараз';
$L['hybridauth_connected'] = 'Підключено';
$L['hybridauth_disconnected'] = 'Відключено';
$L['hybridauth_link'] = 'Прив’язати';
$L['hybridauth_unlink'] = 'Відв’язати';

// ========================
// ФРОНТЕНД – ПОМИЛКИ ТА ПОПЕРЕДЖЕННЯ
// ========================
$L['hybridauth_already_linked'] = 'Цей акаунт уже прив’язано до іншого користувача';
$L['hybridauth_no_linked_account'] = 'Не знайдено облікових записів на сайті, пов’язаних з вашим акаунтом {$provider}. Увійдіть під своїм обліковим записом сайту або зареєструйте новий за кілька кліків.';
$L['hybridauth_email_exists_login'] = 'Користувач з адресою {$email} вже зареєстрований. Будь ласка, увійдіть під своїм обліковим записом та прив’яжіть {$provider} у профілі.';

// Помилки автентифікації (використовуються в hybridauth.php)
$L['hybridauth_error_0'] = 'Невідома помилка.';
$L['hybridauth_error_1'] = 'Помилка конфігурації Hybridauth.';
$L['hybridauth_error_2'] = 'Некоректне налаштування провайдера.';
$L['hybridauth_error_3'] = 'Невідомий провайдер.';
$L['hybridauth_error_4'] = 'Відсутні дані авторизації додатка.';
$L['hybridauth_error_5'] = 'Помилка авторизації. Користувач скасував авторизацію або провайдер розірвав з’єднання.';
$L['hybridauth_error_6'] = 'Неможливо отримати профіль користувача. Швидше за все, потрібно авторизуватися ще раз.';
$L['hybridauth_error_7'] = 'Користувач не підключений до провайдера авторизації.';

// ========================
// ІНСТРУМЕНТИ В АДМІНЦІ (hybridauth.tools.php)
// ========================
$L['hybridauth_fields_list'] = 'Назви полів';
$L['hybridauth_fields_status'] = 'Статус полів';
$L['hybridauth_update_fields'] = 'Створити відсутні поля';
$L['hybridauth_provider_status'] = 'Статус провайдерів';
$L['hybridauth_provider_name'] = 'Провайдер';
$L['hybridauth_provider_code'] = 'Код';
$L['hybridauth_provider_enabled'] = 'Увімкнено';
$L['hybridauth_provider_fields'] = 'Поля';
$L['hybridauth_status_enabled'] = 'Так';
$L['hybridauth_status_disabled'] = 'Ні';
$L['hybridauth_fields_exist'] = 'Створені ✅';
$L['hybridauth_fields_missing'] = 'Відсутні ❌';
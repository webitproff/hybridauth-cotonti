<?php
/**
 * Russian Language File for HybridAuth Plugin
 *
 * Все текстовые строки, используемые плагином в интерфейсе Cotonti:
 * - настройки в админ-панели (cfg_…)
 * - информационные сообщения и названия (info_…)
 * - фронтэнд: кнопки входа, сообщения об ошибках, привязка/отвязка аккаунтов
 * - страница управления полями провайдеров в админке (tools)
 *
 * Filename: plugins/hybridauth/lang/hybridauth.ru.lang.php
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
// НАСТРОЙКИ ПЛАГИНА (АДМИНКА)
// ========================
$L['cfg_autoreg'] = 'Включить авто-регистрацию через соцсети';
$L['cfg_autoreg_hint'] = 'Включить авто-регистрацию через соцсети';
$L['cfg_block_manual_registration'] = 'Блокировать штатную регистрацию';
$L['cfg_block_manual_registration_hint'] = 'Полностью автоматический вход/регистрация через соцсети. Обычная форма регистрации отключена.';

// ========================
// ИНФОРМАЦИЯ О ПЛАГИНЕ (АДМИНКА)
// ========================
$L['info_name'] = 'HybridAuth Авторизация';
$L['info_desc'] = 'Авторизация с помощью Google, Facebook, X/Twitter, OpenID, и других социальных сетей без использования сторонних сервисов';
$L['info_notes'] = 'Отредактируйте plugins/hybridauth/conf/hybridauth.config.php после установки. Каждый раз после добавления новых провайдеров заходите в Администрирование / Конфигурацию / HybridAuth и жмите Обновить.';

// ========================
// ЗАГОЛОВКИ (АДМИНКА, ФРОНТЭНД)
// ========================
$L['hybridauth_title'] = 'HybridAuth Авторизация';
$L['hybridauth'] = 'HybridAuth Авторизация';

// ========================
// ФРОНТЭНД – КНОПКИ И СООБЩЕНИЯ
// ========================
$L['hybridauth_autoreg_inaction'] = 'Штатная форма регистрации отключена. Войдите с помощью вашего аккаунта социальной сети или доступных провайдеров.';
$L['hybridauth_signin_with'] = 'Войти с помощью';
$L['hybridauth_connect'] = 'Подключить сейчас';
$L['hybridauth_connected'] = 'Подключено';
$L['hybridauth_disconnected'] = 'Отключено';
$L['hybridauth_link'] = 'Связать';
$L['hybridauth_unlink'] = 'Отвязать';

// ========================
// ФРОНТЭНД – ОШИБКИ И ПРЕДУПРЕЖДЕНИЯ
// ========================
$L['hybridauth_already_linked'] = 'Этот аккаунт уже связан с другим пользователем';
$L['hybridauth_no_linked_account'] = 'Не обнаружено учетных записей на сайте, связанных с вашей учетной записью {$provider}. Войдите под вашей учетной записью сайта или зарегистрируйте новую с помощью пары кликов.';
$L['hybridauth_email_exists_login'] = 'Пользователь с адресом {$email} уже зарегистрирован. Пожалуйста, войдите под своей учётной записью и привяжите {$provider} в профиле.';

// Ошибки аутентификации (используются в hybridauth.php)
$L['hybridauth_error_0'] = 'Неизвестная ошибка.';
$L['hybridauth_error_1'] = 'Ошибка конфигурации Hybridauth.';
$L['hybridauth_error_2'] = 'Некорректная настройка провайдера.';
$L['hybridauth_error_3'] = 'Неизвестный провайдер.';
$L['hybridauth_error_4'] = 'Отсутствуют данные авторизации приложения.';
$L['hybridauth_error_5'] = 'Ошибка авторизации. Пользователь отменил авторизацию или провайдер разорвал соединение.';
$L['hybridauth_error_6'] = 'Невозможно получить профиль пользователя. Скорее всего, необходимо авторизоваться ещё раз.';
$L['hybridauth_error_7'] = 'Пользователь не подключен к провайдеру авторизации.';

// ========================
// ИНСТРУМЕНТЫ В АДМИНКЕ (hybridauth.tools.php)
// ========================
$L['hybridauth_fields_list'] = 'Имена полей';
$L['hybridauth_fields_status'] = 'Статус полей';
$L['hybridauth_update_fields'] = 'Создать недостающие поля';
$L['hybridauth_provider_status'] = 'Статус провайдеров';
$L['hybridauth_provider_name'] = 'Провайдер';
$L['hybridauth_provider_code'] = 'Код';
$L['hybridauth_provider_enabled'] = 'Включён';
$L['hybridauth_provider_fields'] = 'Поля';
$L['hybridauth_status_enabled'] = 'Да';
$L['hybridauth_status_disabled'] = 'Нет';
$L['hybridauth_fields_exist'] = 'Созданы ✅';
$L['hybridauth_fields_missing'] = 'Отсутствуют ❌';
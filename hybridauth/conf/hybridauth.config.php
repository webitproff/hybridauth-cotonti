<?php
/**
 * Setup & Config File for the Supported Providers of Plugin HybridAuth
 *
 * Основной конфигурационный файл ваших провайдеров для плагина. Содержит:
 * - единый callback-адрес для всех провайдеров (должен совпадать с тем, что указан
 *   в настройках приложений у Google, Telegram и других провайдеров);
 * - список провайдеров с индивидуальными ключами (id, secret) и правами доступа (scope);
 * - глобальный параметр debug_mode и путь к файлу debug-лога библиотеки Hybridauth.
 *
 * Настройка провайдеров выполняется НЕ через админку Cotonti, а непосредственно в этом файле.
 * Для добавления нового провайдера достаточно получить client id/secret в его панели
 * разработчика, прописать их в секции providers и установить enabled = true.
 *
 * Этот файл подключается в inc/hybridauth.functions.php и становится глобальной переменной
 * $hybridauth_config, которая используется контроллером hybridauth.php, всеми хуками плагина
 * и функциями hybridauth_login(), hybridauth_accounts(), hybridauth_complete_profile().
 *
 * @see https://hybridauth.github.io/providers.html — полный список поддерживаемых провайдеров
 * @see https://hybridauth.github.io/developer-ref-user-authentication.html — документация по аутентификации
 * @see https://hybridauth.github.io/developer-ref-providers-apis.html — документация по API провайдеров
 *
 * Filename: plugins/hybridauth/conf/hybridauth.config.php
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

global $cfg;

return [
    'callback' => $cfg['mainurl'] . '/' . 'hybridauth',
    'providers' => [
        'Facebook' => [
            'enabled' => false,
            'keys' => ['id' => '', 'secret' => ''],
            'scope' => 'email, user_birthday',
        ],
        'Google' => [
            'enabled' => false,
            'keys' => [
                'id' => '',
                'secret' => ''
            ],
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
        ],
        'Telegram' => [
            'enabled' => false,
            'keys' => [
                'id'     => '',  // Client ID
                'secret' => '' // Client Secret
            ],
            'scope' => 'openid profile',   // можно добавить 'phone' для номера телефона
        ],
    ],
	// Логирование при отладке
	// Библиотека Hybridauth записывает сюда детальную техническую информацию (заголовки запросов, параметры, ошибки HTTP).
	// включается параметром 'debug_mode' => true 
    'debug_mode' => false,
    'debug_file' => $cfg['plugins_dir'] . '/hybridauth/log/hybridauth_debug.log',
];

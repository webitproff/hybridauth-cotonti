<?php
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Деинсталляционный скрипт. Выполняется при удалении плагина через админ‑панель Cotonti
 * (Конфигурация → Плагины → Удалить).
 *
 * Задача скрипта — удалить из таблицы пользователей (cot_users) столбцы,
 * ранее добавленные для хранения уникальных идентификаторов и URL‑профилей
 * социальных провайдеров (user_google_id, user_google_url и т.д.).
 *
 * Скрипт загружает конфигурацию провайдеров из hybridauth.config.php,
 * перебирает ВСЕХ провайдеров (независимо от флага enabled) и, если столбец
 * существует, удаляет его с помощью ALTER TABLE … DROP COLUMN.
 *
 * Filename: plugins/hybridauth/setup/hybridauth.uninstall.php
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

global $db, $db_users;

$config = include __DIR__ . '/../conf/hybridauth.config.php';
foreach ($config['providers'] as $key => $val) {
    $name = strtolower($key);
    $fields = ["user_{$name}_id", "user_{$name}_url"];
    foreach ($fields as $field_name) {
        if ($db->fieldExists($db_users, $field_name)) {
            $db->query("ALTER TABLE `$db_users` DROP COLUMN `$field_name`");
        }
    }
}
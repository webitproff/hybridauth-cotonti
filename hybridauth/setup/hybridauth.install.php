<?php
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Установочный скрипт. Выполняется при активации плагина в админ‑панели Cotonti
 * (Конфигурация → Плагины → Установить). Также может быть вызван повторно при нажатии
 * кнопки «Обновить» в настройках плагина.
 *
 * Задача скрипта — динамически добавить в таблицу пользователей (cot_users)
 * столбцы для хранения уникальных идентификаторов и URL‑профилей каждого
 * включённого провайдера (Google, Telegram и других). Имена столбцов формируются
 * по шаблону:
 *   user_{provider_code}_id   (например, user_google_id)
 *   user_{provider_code}_url  (например, user_google_url)
 *
 * Скрипт загружает конфигурацию провайдеров из hybridauth.config.php,
 * перебирает все включённые провайдеры и, если нужный столбец отсутствует,
 * добавляет его с помощью ALTER TABLE. Тип поля: VARCHAR(96) DEFAULT NULL.
 *
 * Filename: plugins/hybridauth/setup/hybridauth.install.php
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
    if ($val['enabled']) {
        $fields = ["user_{$name}_id", "user_{$name}_url"];
        foreach ($fields as $field_name) {
            if (!$db->fieldExists($db_users, $field_name)) {
                $db->query("ALTER TABLE `$db_users` ADD COLUMN `$field_name` VARCHAR(96) DEFAULT NULL");
            }
        }
    }
}
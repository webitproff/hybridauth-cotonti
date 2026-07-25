<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4 
 * Хук tools. Добавляем плагину интерфейс и инструменты управления.
 * Админка плагина. Просмотр статусов провайдеров, внесенных в hybridauth.config.php. 
 * Создаём поля "user_{$name}_id", "user_{$name}_url" в $db_users для включённых провайдеров
 * Filename: plugins/hybridauth/hybridauth.tools.php 
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
global $db, $db_users, $hybridauth_config, $L;

$action = cot_import('a', 'G', 'ALP');

// Если админ нажал кнопку — создаём поля для включённых провайдеров
if ($action === 'update') {
    foreach ($hybridauth_config['providers'] as $key => $val) {
        $name = strtolower($key);
        if ($val['enabled']) {
            $fields = ["user_{$name}_id", "user_{$name}_url"];
            foreach ($fields as $field_name) {
                if (!$db->fieldExists($db_users, $field_name)) {
                    $db->query("ALTER TABLE `$db_users` ADD COLUMN `$field_name` VARCHAR(96) DEFAULT NULL");
                    cot_message(cot_rc('Added', $field_name));
                } else {
                    cot_message("Поле $field_name уже существует");
                }
            }
        }
    }
    // После создания полей — перенаправляем на ту же страницу без параметра a, чтобы сбросить сообщения
    cot_redirect(cot_url('admin', 'm=other&p=hybridauth', '', true));
}

// Собираем данные о провайдерах для таблицы
$provider_data = [];
foreach ($hybridauth_config['providers'] as $key => $val) {
    $name = strtolower($key);
    $fields = ["user_{$name}_id", "user_{$name}_url"];
    $all_exist = true;
    foreach ($fields as $field_name) {
        if (!$db->fieldExists($db_users, $field_name)) {
            $all_exist = false;
            break;
        }
    }
    $provider_data[] = [
        'NAME' => $key,
        'CODE' => $name,
        'ENABLED' => $val['enabled'] ? $L['hybridauth_status_enabled'] : $L['hybridauth_status_disabled'],
        'FIELDS' => $all_exist ? $L['hybridauth_fields_exist'] : $L['hybridauth_fields_missing'],
        'FIELDS_LIST' => implode(', ', $fields), // список имён полей
    ];
}

$tt = new XTemplate(cot_tplfile('hybridauth.tools', 'plug'));
cot_display_messages($tt);

// Вывод таблицы
foreach ($provider_data as $row) {
    $tt->assign([
        'PROVIDER_NAME' => $row['NAME'],
        'PROVIDER_CODE' => $row['CODE'],
        'PROVIDER_ENABLED' => $row['ENABLED'],
        'PROVIDER_FIELDS' => $row['FIELDS'],
        'PROVIDER_FIELDS_LIST' => $row['FIELDS_LIST'],
    ]);
    $tt->parse('MAIN.PROVIDER_ROW');
}

$tt->assign([
    'HYBRIDAUTH_UPDATE_URL' => cot_url('admin', 'm=other&p=hybridauth&a=update'),
    'HYBRIDAUTH_LANG_UPDATE' => $L['hybridauth_update_fields'],
    'HYBRIDAUTH_LANG_TITLE' => $L['hybridauth_title'],
    'HYBRIDAUTH_LANG_STATUS' => $L['hybridauth_provider_status'],
    'HYBRIDAUTH_LANG_NAME' => $L['hybridauth_provider_name'],
    'HYBRIDAUTH_LANG_CODE' => $L['hybridauth_provider_code'],
    'HYBRIDAUTH_LANG_ENABLED' => $L['hybridauth_provider_enabled'],
    'HYBRIDAUTH_LANG_FIELDS' => $L['hybridauth_provider_fields'],
    'HYBRIDAUTH_LANG_FIELDS_LIST' => $L['hybridauth_fields_list'],
    'HYBRIDAUTH_LANG_FIELDS_STATUS' => $L['hybridauth_fields_status'],
]);

$tt->parse();
$pluginBody = $tt->text('MAIN');
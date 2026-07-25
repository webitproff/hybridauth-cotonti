<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=usertags.main
[END_COT_EXT]
==================== */
/**
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 * Filename: plugins/hybridauth/hybridauth.usertags.php - Overrides user tags in cot_generate_usertags() function
 * Теги для использования в cot_generate_usertags() (списки, карточки, форум, etc.)
 * Хук usertags.main. Добавляет в общий массив тегов переменные $temp_array + ИМЯПОЛЯ
 * Логика файла hybridauth.usertags.php (добавление тегов) также опирается на $hybridauth_config, 
 * чтобы для каждого включённого провайдера создать переменные шаблона, например: 
 * ({USERS_ROW_GOOGLE_ID}, {USERS_DETAILS_GOOGLE_ID}, {USERS_ROW_TELEGRAM_URL}, {USERS_DETAILS_TELEGRAM_URL} и т.д.) 
 *
 * HybridAuth plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
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
 *
 * @see cot_generate_usertags()
 * Хук usertags.main вызывается внутри функции cot_generate_usertags().
 * Переменная $temp_array доступна напрямую, без объявления global.
 * @var array<string, mixed> $user_data
 */



defined('COT_CODE') or die('Wrong URL');

// Работаем только если есть реальный пользователь (как в pm, userimages)
if (!is_array($user_data) || empty($user_data['user_id']) || empty($user_data['user_name'])) {
    return;
}

require_once cot_incfile('hybridauth', 'plug');

global $hybridauth_config;

// Sync extra fields if necessary
foreach ($hybridauth_config['providers'] as $key => $val)
{
	$name = strtolower($key);
	if ($val['enabled'])
	{
		$fields = array("{$name}_id", "{$name}_url");
		foreach ($fields as $field_name)
		{
			$temp_array[strtoupper($field_name)] = $user_data['user_' . $field_name];
		}
	}
}

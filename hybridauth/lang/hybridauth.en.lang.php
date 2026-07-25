<?php
/**
 * English Language File for HybridAuth Plugin
 *
 * All text strings used by the plugin in the Cotonti interface:
 * - admin panel settings (cfg_…)
 * - information messages and names (info_…)
 * - frontend: login buttons, error messages, account linking/unlinking
 * - provider field management page in admin (tools)
 *
 * Filename: plugins/hybridauth/lang/hybridauth.en.lang.php
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
// PLUGIN SETTINGS (ADMIN PANEL)
// ========================
$L['cfg_autoreg'] = 'Enable auto-registration via social networks';
$L['cfg_autoreg_hint'] = 'Enable auto-registration via social networks';
$L['cfg_block_manual_registration'] = 'Block standard registration';
$L['cfg_block_manual_registration_hint'] = 'Fully automatic login/registration via social networks. The standard registration form is disabled.';

// ========================
// PLUGIN INFO (ADMIN PANEL)
// ========================
$L['info_name'] = 'HybridAuth Authentication';
$L['info_desc'] = 'Authentication via Google, Facebook, X/Twitter, OpenID, and other social networks without third-party services';
$L['info_notes'] = 'Edit plugins/hybridauth/conf/hybridauth.config.php after installation. Each time after adding new providers, go to Administration / Configuration / HybridAuth and click Update.';

// ========================
// HEADINGS (ADMIN PANEL, FRONTEND)
// ========================
$L['hybridauth_title'] = 'HybridAuth Authentication';
$L['hybridauth'] = 'HybridAuth Authentication';

// ========================
// FRONTEND – BUTTONS AND MESSAGES
// ========================
$L['hybridauth_autoreg_inaction'] = 'Standard registration is disabled. Please sign in using your social network account or available providers.';
$L['hybridauth_signin_with'] = 'Sign in with';
$L['hybridauth_connect'] = 'Connect now';
$L['hybridauth_connected'] = 'Connected';
$L['hybridauth_disconnected'] = 'Disconnected';
$L['hybridauth_link'] = 'Link';
$L['hybridauth_unlink'] = 'Unlink';

// ========================
// FRONTEND – ERRORS AND WARNINGS
// ========================
$L['hybridauth_already_linked'] = 'This account is already linked to another user';
$L['hybridauth_no_linked_account'] = 'No site accounts found linked to your {$provider} account. Sign in with your site account or register a new one in a couple of clicks.';
$L['hybridauth_email_exists_login'] = 'A user with the email {$email} is already registered. Please sign in with your account and link {$provider} in your profile.';

// Authentication errors (used in hybridauth.php)
$L['hybridauth_error_0'] = 'Unknown error.';
$L['hybridauth_error_1'] = 'Hybridauth configuration error.';
$L['hybridauth_error_2'] = 'Incorrect provider configuration.';
$L['hybridauth_error_3'] = 'Unknown provider.';
$L['hybridauth_error_4'] = 'Application authorization data is missing.';
$L['hybridauth_error_5'] = 'Authentication error. The user cancelled the authorization or the provider dropped the connection.';
$L['hybridauth_error_6'] = 'Unable to retrieve user profile. Most likely, you need to log in again.';
$L['hybridauth_error_7'] = 'User is not connected to the authentication provider.';

// ========================
// ADMIN TOOLS (hybridauth.tools.php)
// ========================
$L['hybridauth_fields_list'] = 'Field names';
$L['hybridauth_fields_status'] = 'Field status';
$L['hybridauth_update_fields'] = 'Create missing fields';
$L['hybridauth_provider_status'] = 'Provider status';
$L['hybridauth_provider_name'] = 'Provider';
$L['hybridauth_provider_code'] = 'Code';
$L['hybridauth_provider_enabled'] = 'Enabled';
$L['hybridauth_provider_fields'] = 'Fields';
$L['hybridauth_status_enabled'] = 'Yes';
$L['hybridauth_status_disabled'] = 'No';
$L['hybridauth_fields_exist'] = 'Created ✅';
$L['hybridauth_fields_missing'] = 'Missing ❌';
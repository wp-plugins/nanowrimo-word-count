<?php
/*
Plugin Name: NaNoWriMo Word Count
Plugin URI: http://thejakeneumann.com
Description: A plugin so that an author (you) can track your word count for NaNoWriMo
Version: 1.0
Author: The Jake Neumann
Author URI: http://thajekenumann.com
*/

// some definition we will use
define( 'nwc_PUGIN_NAME', 'NaNoWriMo Word Count');
define( 'nwc_PLUGIN_DIRECTORY', 'nano-wc');
define( 'nwc_CURRENT_VERSION', '0.1' );
define( 'nwc_CURRENT_BUILD', '' );
define( 'nwc_LOGPATH', str_replace('\\', '/', WP_CONTENT_DIR).'/nanowc-logs/');
define( 'nwc_DEBUG', false);		# never use debug mode on productive systems
// i18n plugin domain for language files
define( 'EMU2_I18N_DOMAIN', 'nanowc' );

// load language files
function nwc_set_lang_file() {
	# set the language file
	$currentLocale = get_locale();
	if(!empty($currentLocale)) {
		$moFile = dirname(__FILE__) . "/lang/" . $currentLocale . ".mo";
		if (@file_exists($moFile) && is_readable($moFile)) {
			load_textdomain(EMU2_I18N_DOMAIN, $moFile);
		}
	}
}
nwc_set_lang_file();

// create custom plugin settings menu
add_action( 'admin_menu', 'nwc_create_menu' );

//call register settings function
add_action( 'admin_init', 'nwc_register_settings' );
add_filter( 'plugin_action_links','nwc_plugin_action_links',10,2);

register_activation_hook(__FILE__, 'nwc_activate');
register_deactivation_hook(__FILE__, 'nwc_deactivate');
register_uninstall_hook(__FILE__, 'nwc_uninstall');

// activating the default values
function nwc_activate() {
	add_option('nwc_option_3', 'any_value');
}

// deactivating
function nwc_deactivate() {
	// needed for proper deletion of every option
	delete_option('nwc_option_3');
}

// uninstalling
function nwc_uninstall() {
	# delete all data stored
	delete_option('nwc_option_3');
	// delete log files and folder only if needed
	if (function_exists('nwc_deleteLogFolder')) nwc_deleteLogFolder();
}

function nwc_register_settings() {
    //register settings
    register_setting( 'nanowc_settings_group', 'new_option_name' );
    register_setting( 'nanowc_settings_group', 'page_option' );
    register_setting( 'nanowc_settings_group', 'cat' );
}

function nwc_create_menu() {
	// or create options menu page
	add_plugins_page(__(nwc_PUGIN_NAME, EMU2_I18N_DOMAIN), __("NaNoWriMo Word Count", EMU2_I18N_DOMAIN), 9,  nwc_PLUGIN_DIRECTORY.'/nano-settings.php');
}

function nwc_plugin_action_links( $links, $file ) {
    //Static so we don't call plugin_basename on every plugin row.
    static $this_plugin;
    if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

    if ( $file == $this_plugin ){
         $settings_link = '<a href="plugins.php?page=nano-wc/nano-settings.php">' . __('Settings', 'nano-wc') . '</a>';
         array_unshift( $links, $settings_link );
    }
    return $links;
}

// check if debug is activated
function nwc_debug() {
	# only run debug on localhost
	if ($_SERVER["HTTP_HOST"]=="localhost" && defined('EPS_DEBUG') && EPS_DEBUG==true) return true;
}

// where the magic happens
include('nwc-word-count.php');
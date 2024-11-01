<?php
/*
 * Plugin Name:       WPTelMessage
 * Plugin URI:        https://prowebcode.ru/plagin-wptelmessage/
 * Description:       Using this plugin you can send messages from the site directly to the telegram channel.
 * Version:           1.0
 * Requires at least: 5.2
 * Tested up to:      6.6
 * Requires PHP:      7.4
 * Author:            HRCode
 * Author URI:        https://prowebcode.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wptelmessage
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || die();

if ( defined( 'HRCWTM_PRO' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-includes/pluggable.php';
    if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); 
		wp_safe_redirect( add_query_arg( 'deactivate', 'true', remove_query_arg( 'activate' ) ) );
		return;
	}
} else {

    define( 'HRCWTM_DIR', plugin_dir_path( __FILE__ ) );
    define( 'HRCWTM_URL', plugin_dir_url( __FILE__ ) );
    define( 'HRCWTM_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
    define( 'HRCWTM_BASENAME', plugin_basename( __FILE__ ) );
    define( 'HRCWTM_FUNC', HRCWTM_DIR . 'assets/js/hrcwtm-functions.php' );

    require_once( HRCWTM_DIR . 'autoload.php' );
    
    Hrcode\WpTelMessage\WPTelMessageForm::init();
    Hrcode\WpTelMessage\WPTelMessageWoo::init();
    
    add_action( 'plugins_loaded', 'hrcwtm_languages' );
    add_action( 'admin_init', array( Hrcode\WpTelMessage\WpTelMessageSetting::class, 'hook_admin_init' ) );
    add_action( 'admin_menu', array( Hrcode\WpTelMessage\WpTelMessageSetting::class, 'initialization_menu' ) );
    
    function hrcwtm_languages() {
        load_plugin_textdomain( 'wptelmessage', false, HRCWTM_PLUGIN_DIRNAME . '/languages' );
    }
}

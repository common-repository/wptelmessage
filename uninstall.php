<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link      https://prowebcode.ru/
 * @since     1.0.0
 * @package plugin WPTelmessage
 * 
 */
 
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if( is_multisite() ){
	$blog_ids = get_sites( [ 'fields' => 'ids' ] );

	foreach( $blog_ids as $blog_id ){
		switch_to_blog( $blog_id );
		hrcwtm_uninstall();
		restore_current_blog();
	}
}
else {
	hrcwtm_uninstall();
}

function hrcwtm_uninstall(){
   global $wpdb;
   $prefix = 'hrcwtm_';
   $options_to_delete = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s", $prefix . '%' ) );
   foreach ( $options_to_delete as $option ) {
            delete_option( $option );
        }
}
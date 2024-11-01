<?php

namespace Hrcode\WpTelMessage;

defined( 'ABSPATH' ) || die();

class WpTelMessageSetting {
    // General
    const PAGE_SLUG = 'wptelmessage';
    const GROUP_NAME = 'hrcwtm_plugin_menu';

    // Option
    const GROUP_ID = 'hrcdwtm_group_id';
    const BOT_TOKEN = 'hrcwtm_bot_token';
    const CF7_ACTIVATION = 'hrcwtm_cf7_activation';
    const WF_ACTIVATION = 'hrcwtm_wpforms_activation';
    const NF_ACTIVATION = 'hrcwtm_ninjaforms_activation';
    const WOO_ACTIVATION = 'hrcwtm_woo_activation';
    const ADD_CART = 'hrcwtm_woo_activation_add_to_cart' ;
    const REMOVE_CART = 'hrcwtm_woo_activation_remove_from_cart';
    const ORDER_STATUS_CHANGED = 'hrcwtm_woo_activation_order_status_changed';
    const ORDER_STATUS_COMPLETED = 'hrcwtm_woo_activation_order_status_completed';
    const LOW_STOCK_NOTIFICATION = 'hrcwtm_woo_activation_low_stock_notification';

    // Section
    const CONF_SECTION = 'hrcwtm_configuration_section';
    const CF7_SECTION = 'hrcwtm_cf7_section';
    const WF_SECTION = 'hrcwtm_wpforms_section';
    const NF_SECTION = 'hrcwtm_ninja_form_section';
    const WOO_SECTION = 'hrcwtm_woo_section';

    public static function hook_admin_init() {
        self::hrcwtm_settings_field();
        self::hrcwtm_processing_form_data();
    }

    public static function hrcwtm_settings_field() {

        $sections = array(
            array( 'section_id' => self::CONF_SECTION, 'title' => __( 'Configuration', 'wptelmessage' ) ),
            array( 'section_id' => self::CF7_SECTION, 'title' => __( 'Contact Form 7', 'wptelmessage' ) ),
            array( 'section_id' => self::WF_SECTION, 'title' => __( 'WPForms', 'wptelmessage' ) ),
            array( 'section_id' => self::NF_SECTION, 'title' => __( 'NinjaForms', 'wptelmessage' ) ),
            array( 'section_id' => self::WOO_SECTION, 'title' => __( 'WooCommerce', 'wptelmessage' ) )
        );

        foreach ( $sections as $section ) {
            $section_id = $section['section_id'];
            $title = $section['title'];
            self::hrcwtm_add_settings_section( $section_id, $title, '', self::PAGE_SLUG );
        }

        $register_fields = array(
            array( 'id' => self::GROUP_ID, 'callback' => 'sanitize_text_field', 'type' => 'string', 'default' => NULL ),
            array( 'id' => self::BOT_TOKEN, 'callback' => 'sanitize_text_field', 'type' => 'string', 'default' => NULL ),
            array( 'id' => self::CF7_ACTIVATION, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false ),
            array( 'id' => self::WF_ACTIVATION, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false ),
            array( 'id' => self::NF_ACTIVATION, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false ),
            array( 'id' => self::WOO_ACTIVATION, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false ),
            array( 'id' => self::ADD_CART, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false ),
            array( 'id' => self::REMOVE_CART, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false ),
            array( 'id' => self::ORDER_STATUS_CHANGED, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false ),
            array( 'id' => self::ORDER_STATUS_COMPLETED, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false ),
            array( 'id' => self::LOW_STOCK_NOTIFICATION, 'callback' => 'sanitize_text_field', 'type' => 'boolean', 'default' => false )
        );

        foreach( $register_fields as $register_field ) {
            register_setting( self::GROUP_NAME, $register_field['id'], array( 'type' => $register_field['type'], 'sanitize_callback' => $register_field['callback'], 'default' => $register_field['default'] ) );
        }

        $fields = array(
            array( 'id' => self::GROUP_ID, 'label' => __( 'Group ID', 'wptelmessage' ), 'callback' => 'hrcwtm_render_group_id_field', 'section' => self::CONF_SECTION ),
            array( 'id' => self::BOT_TOKEN, 'label' => __( 'Bot Token', 'wptelmessage' ), 'callback' => 'hrcwtm_render_bot_token_field', 'section' => self::CONF_SECTION ),
            array( 'id' => self::CF7_ACTIVATION, 'label' => __( 'Receive messages from Contact Form 7', 'wptelmessage'), 'callback' => 'hrcwtm_cf7_activation_section', 'section' => self::CF7_SECTION ),
            array( 'id' => self::WF_ACTIVATION, 'label' => __( 'Receive messages from WPForms', 'wptelmessage' ), 'callback' => 'hrcwtm_wpforms_activation_section', 'section' => self::WF_SECTION ),
            array( 'id' => self::NF_ACTIVATION, 'label' => __( 'Receive messages from NinjaForms', 'wptelmessage' ), 'callback' => 'hrcwtm_nforms_activation_section', 'section' => self::NF_SECTION ),
            array( 'id' => self::WOO_ACTIVATION, 'label' => __( 'Receive messages from WooCommerce', 'wptelmessage' ), 'callback' => 'hrcwtm_woo_activation_section', 'section' => self::WOO_SECTION ),
            array( 'id' => self::ADD_CART, 'label' => __( 'Receive messages for an event', 'wptelmessage' ), 'callback' => 'hrcwtm_woo_activation_add_to_cart', 'section' => self::WOO_SECTION ),
            array( 'id' => self::REMOVE_CART, 'label' => '', 'callback' => 'hrcwtm_woo_activation_remove_from_cart', 'section' => self::WOO_SECTION ),
            array( 'id' => self::ORDER_STATUS_CHANGED, 'label' => '', 'callback' => 'hrcwtm_woo_activation_order_status_changed', 'section' => self::WOO_SECTION ),
            array( 'id' => self::ORDER_STATUS_COMPLETED, 'label' => '', 'callback' => 'hrcwtm_woo_activation_order_status_completed', 'section' => self::WOO_SECTION ),
            array( 'id' => self::LOW_STOCK_NOTIFICATION, 'label' => '', 'callback' => 'hrcwtm_woo_activation_low_stock_notification', 'section' => self::WOO_SECTION ),
        );
        foreach( $fields as $field ) {
            add_settings_field( $field['id'], $field['label'], array(__CLASS__, $field['callback']), self::PAGE_SLUG, $field['section'] );
        }
    }

    // Creating token and group input fields
    public static function hrcwtm_render_data_field( $option_name ) {
        $option_value = get_option( $option_name, '' );
        echo '<input type="text" class="hrcwtm-input" name="' . esc_attr( $option_name ) . '" value="' . esc_attr($option_value) . '" />';
    }

    // Render group ID field
    public static function hrcwtm_render_group_id_field() {
        self::hrcwtm_render_data_field( self::GROUP_ID );
    }

    // Render bot token field
    public static function hrcwtm_render_bot_token_field() {
        self::hrcwtm_render_data_field( self::BOT_TOKEN );
    }

    // Processing form data
    public static function hrcwtm_processing_form_data() {
        $data_fields = [self::GROUP_ID, self::BOT_TOKEN];

        foreach ( $data_fields as $field ) {
            if ( isset( $_POST[$field] ) ) {
                $data_field_value = sanitize_text_field( $_POST[$field] );
                update_option( $field, $data_field_value );
            }
        }
    }

    // Headers
    public static function hrcwtm_add_settings_section( $id, $title, $callback, $page, $args = array() ) {
        $titleHtml = '<h3 class="hrcwtm-options__title">' . $title . '</h3>';
        add_settings_section( $id, '', $callback, $page, ['before_section' => $titleHtml] );
    }

    // CF7
    public static function hrcwtm_cf7_activation_section() {
        $option_name = self::CF7_ACTIVATION;
        $option_value = get_option( $option_name, 0 );

        if ( defined( 'WPCF7_VERSION' ) && is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
            $checked = checked( 1, $option_value, false );

            echo '<label for="' . esc_attr( $option_name ) . '">';
            printf('<input type="checkbox" id="%s" name="%s" value="1" %s>', esc_attr( $option_name ), esc_attr( $option_name ), esc_attr( $checked ) );
            esc_html_e( 'Activate Contact Form 7', 'wptelmessage' );
            echo '</label>';
        } else {
            printf( '<input type="checkbox" id="%s" name="%s" value="1" disabled>', esc_attr( $option_name ), esc_attr( $option_name) );
            esc_html_e( 'Contact Form 7 plugin is not activated', 'wptelmessage' );
        }
    }

    // WPForms
    public static function hrcwtm_wpforms_activation_section() {
        $option_name = self::WF_ACTIVATION;
        $option_value = get_option( $option_name, 0 );

        if ( class_exists( 'WPForms' ) && is_plugin_active( 'wpforms-lite/wpforms.php' ) ) {
            $checked = checked( 1, $option_value, false );

            echo '<label for="' . esc_attr( $option_name ) . '">';
            printf('<input type="checkbox" id="%s" name="%s" value="1" %s>', esc_attr( $option_name ), esc_attr( $option_name ), esc_attr( $checked ) );
            esc_html_e( 'Activate WPForms', 'wptelmessage' );
            echo '</label>';
        }else {
            printf( '<input type="checkbox" id="%s" name="%s" value="1" disabled>', esc_attr( $option_name ), esc_attr( $option_name) );
            esc_html_e( 'WPForms plugin is not activated', 'wptelmessage' );
        }
    }

    // NinjaForms
    public static function hrcwtm_nforms_activation_section() {
        $option_name = self::NF_ACTIVATION;
        $option_value = get_option( $option_name, 0 );
        
        if ( class_exists( 'Ninja_Forms' ) && is_plugin_active( 'ninja-forms/ninja-forms.php' ) ) {
            $checked = checked( 1, $option_value, false );

            echo '<label for="' . esc_attr( $option_name ) . '">';
            printf('<input type="checkbox" id="%s" name="%s" value="1" %s>', esc_attr( $option_name ), esc_attr( $option_name ), esc_attr( $checked ) );
            esc_html_e( 'Activate NinjaForms', 'wptelmessage' );
            echo '</label>';
        } else {
            printf( '<input type="checkbox" id="%s" name="%s" value="1" disabled>', esc_attr( $option_name ), esc_attr( $option_name) );
            esc_html_e( 'Ninja Forms plugin is not activated', 'wptelmessage' );
        }
    }

    // WooCommerce
    public static function hrcwtm_settings_woo( $option_name, $label ) {
        $option_value = get_option( $option_name, 0 );
        $checked = checked( 1, $option_value, false );

        $woocommerce_active = class_exists( 'WooCommerce' ) && is_plugin_active( 'woocommerce/woocommerce.php' );
        echo '<label for="' . esc_attr( $option_name ) . '">';
        if ( $woocommerce_active ) {

            printf('<input type="checkbox" id="%s" name="%s" value="1" %s>', esc_attr( $option_name ), esc_attr( $option_name ), esc_attr( $checked ) );
            echo esc_attr( $label );

        } else {
            printf( '<input type="checkbox" id="%s" name="%s" value="1" disabled>', esc_attr( $option_name ), esc_attr( $option_name) );
            esc_html_e( 'WooCommerce plugin is not activated', 'wptelmessage' );
        }
        echo '</label>';
    }

    public static function hrcwtm_woo_activation_section() {
        self::hrcwtm_settings_woo(self::WOO_ACTIVATION, __( 'Activate WooCommerce', 'wptelmessage' ) );
    }

    public static function hrcwtm_woo_activation_add_to_cart() {
        self::hrcwtm_settings_woo(self::ADD_CART, __( 'Adding an item to your cart', 'wptelmessage' ) );
    }

    public static function hrcwtm_woo_activation_remove_from_cart() {
        self::hrcwtm_settings_woo(self::REMOVE_CART, __( 'Removing an item from the cart', 'wptelmessage' ) );
    }

    public static function hrcwtm_woo_activation_order_status_changed() {
        self::hrcwtm_settings_woo(self::ORDER_STATUS_CHANGED, __( 'Changing product status', 'wptelmessage' ) );
    }

    public static function hrcwtm_woo_activation_order_status_completed() {
        self::hrcwtm_settings_woo(self::ORDER_STATUS_COMPLETED, __( 'Completing your purchase', 'wptelmessage' ) );
    }

    public static function hrcwtm_woo_activation_low_stock_notification() {
        self::hrcwtm_settings_woo(self::LOW_STOCK_NOTIFICATION, __( 'Low stock of goods', 'wptelmessage' ) );
    }

    public static function initialization_menu(){
        add_menu_page( __( 'Basic settings WPTelMessage', 'wptelmessage' ), 'WPTelMessage', 'manage_options', self::PAGE_SLUG, array(__CLASS__, 'main_page_settings' ), 'dashicons-email', 93 );
    }

    public static function main_page_settings(){
        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( esc_html_e( 'You do not have access to view this page.', 'wptelmessage' ) );
        }
        settings_errors();

        wp_enqueue_style( 'hrcwtm-style', HRCWTM_URL.'assets/css/hrcwtm-style.css', false, '1.0', 'all' );

        include_once HRCWTM_FUNC;
        hrcwtm_include( 'templates/hrcwtm-admin-page.php' );
    }
}
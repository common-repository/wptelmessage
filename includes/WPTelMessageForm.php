<?php

namespace Hrcode\WpTelMessage;

defined( 'ABSPATH' ) || die();

use Hrcode\WpTelMessage\WpTelMessageSetting;
use WPCF7_Submission;

class WPTelMessageForm {
    static $website_name;
    static $field_names;

    public static function init() {
        self::prepareFields();

        add_action( 'wpcf7_mail_sent', array( __CLASS__, 'hrcwtm_cf7_send_to_telegram' ), 10, 1 );
        add_action( 'wpforms_process_complete', array( __CLASS__, 'hrcwtm_wpforms_send_to_telegram' ), 10, 4 );
        add_action( 'ninja_forms_after_submission', array( __CLASS__, 'hrcwtm_ninjaforms_send_to_telegram' ), 10, 1 );
    }

    public static function prepareFields() {
        self::$website_name = get_bloginfo( 'name' );

        self::$field_names = array(
            'your-name'     => __( 'Name', 'wptelmessage' ),
            'your-email'    => __( 'Email', 'wptelmessage' ),
            'your-subject'  => __( 'Subject', 'wptelmessage' ),
            'your-message'  => __( 'Message', 'wptelmessage' ),
            'your-phone'    => __( 'Phone', 'wptelmessage' ),
            'your-address'  => __( 'Address', 'wptelmessage' ),
            'your-city'     => __( 'City', 'wptelmessage' ),
            'your-state'    => __( 'State', 'wptelmessage' ),
            'your-zip'      => __( 'Zip', 'wptelmessage' ),
            'your-country'  => __( 'Country', 'wptelmessage' ),
            'your-company'  => __( 'Company', 'wptelmessage' ),
            'your-file'     => __( 'File', 'wptelmessage' ),
        );
    }

    // Send message telegram
    private static function hrcwtm_send_message_to_telegram( $group_id, $bot_token, $message ) {
        $telegram_message = urlencode( $message );
        $telegram_url = "https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$group_id&text=$telegram_message";
        wp_remote_get( $telegram_url );
    }

    // Converting CF7 tags
    private static function hrcwtm_convert_posted_data_to_message( $posted_data ) {
        $message = '';
        $black_list = array(
            '_wpcf7',
            '_wpcf7_version',
            '_wpcf7_locale',
            '_wpcf7_unit_tag',
            '_wpcf7_container_post',
            '_wpcf7_nonce'
        );

        foreach ( $posted_data as $key => $value ) {
            if ( in_array( $key, $black_list ) ) {
                continue;
            }

            $message .= isset( self::$field_names[$key] ) && $value !== self::$field_names[$key]
                ? self::$field_names[$key] . ": " . $value . "\n"
                : $key . ': ' . $value . "\n";
        }

        return $message;
    }

    // CF7
    public static function hrcwtm_cf7_send_to_telegram( $contact_form ) {
        $afscf7 = get_option( WpTelMessageSetting::CF7_ACTIVATION );
        $submission = WPCF7_Submission::get_instance();

        if ( $submission && $afscf7 ) {
            $group_id = get_option( WpTelMessageSetting::GROUP_ID, '' );
            $bot_token = get_option( WpTelMessageSetting::BOT_TOKEN, '' );
            $form_title = $contact_form->title;
            $posted_data = $submission->get_posted_data();
            $message = __( 'Message from the website:', 'wptelmessage' ) . ' ' . self::$website_name . "\n\n";
            $message .= __( 'Form:', 'wptelmessage' ) . ' ' . $form_title . "\n\n";
            $message .= self::hrcwtm_convert_posted_data_to_message( $posted_data );

            self::hrcwtm_send_message_to_telegram( $group_id, $bot_token, $message );
        }
    }

    // WPForms
    public static function hrcwtm_wpforms_send_to_telegram( $fields, $entry, $form_data, $entry_id ) {
        $afswpforms = get_option( WpTelMessageSetting::WF_ACTIVATION );

        if ( $afswpforms ) {
            $group_id = get_option( WpTelMessageSetting::GROUP_ID, '' );
            $bot_token = get_option( WpTelMessageSetting::BOT_TOKEN, '' );
            $form_title = $form_data['settings']['form_title'];
            $message = __( 'Message from the website:', 'wptelmessage' ) . ' ' . self::$website_name . "\n\n";
            $message .= __( 'Form:', 'wptelmessage' ) . ' ' . $form_title . "\n\n";

            foreach ($fields as $field) {
                $field_name = isset($field['name']) ? $field['name'] : '';
                $field_value = isset($field['value']) ? $field['value'] : '';

                if ( in_array( $field_name, ['g-recaptcha-response'] ) ) {
                    continue;
                }

                $message .= $field_name . ': ' . $field_value . "\n";
            }

            self::hrcwtm_send_message_to_telegram( $group_id, $bot_token, $message );
        }
    }

    // NinjaForms
    public static function hrcwtm_ninjaforms_send_to_telegram( $form_data ) {
        $afsnforms = get_option( WpTelMessageSetting::NF_ACTIVATION );

        if ( $afsnforms ) {
            $group_id = get_option( WpTelMessageSetting::GROUP_ID, '' );
            $bot_token = get_option( WpTelMessageSetting::BOT_TOKEN, '' );
            $fields = $form_data['fields'];
            $form_title = $form_data['settings']['title'];
            $message = __( 'Message from the website:', 'wptelmessage' ) . ' ' . self::$website_name . "\n\n";
            $message .= __( 'Form:', 'wptelmessage' ) . ' ' . $form_title . "\n\n";

            foreach ( $fields as $field ) {
                $field_label = isset($field['label']) ? $field['label'] : '';
                $field_value = isset($field['value']) ? $field['value'] : '';

                if ( empty( $field_label ) || empty( $field_value ) ) {
                    continue;
                }

                $message .= $field_label . ': ' . $field_value . "\n";
            }

            self::hrcwtm_send_message_to_telegram( $group_id, $bot_token, $message );
        }
    }
}
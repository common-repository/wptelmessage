<?php

namespace Hrcode\WpTelMessage;

defined( 'ABSPATH' ) || die();

use Hrcode\WpTelMessage\WpTelMessageSetting;

class WPTelMessageWoo {
    public static function init() {
        add_action( 'woocommerce_add_to_cart', array( __CLASS__, 'hrcwtm_woo_add_to_cart' ), 10, 6 );
        add_action( 'woocommerce_remove_cart_item', array( __CLASS__, 'hrcwtm_woo_remove_from_cart' ), 10, 2 );
        add_action( 'woocommerce_order_status_changed', array( __CLASS__, 'hrcwtm_woo_order_status_changed' ), 10, 4 );
        add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'hrcwtm_woo_order_status_completed'), 10, 1 );
        add_action( 'woocommerce_low_stock', array( __CLASS__,'hrcwtm_woo_low_stock_notification'), 10, 1 );
    }

    // User information
    private static function hrcwtm_get_current_user_info() {
        $current_user = wp_get_current_user();
        $user_info = array(
            'display_name' => $current_user->display_name,
            'username' => $current_user->user_login
        );
        return $user_info;
    }

    // Sending messages in telegram
    private static function hrcwtm_woo_send_to_telegram( $message ) {
        $afswoo = get_option( WpTelMessageSetting::WOO_ACTIVATION );
        if ( $afswoo ) {
            $group_id = get_option( WpTelMessageSetting::GROUP_ID, '' );
            $bot_token = get_option( WpTelMessageSetting::BOT_TOKEN, '' );

            $telegram_url = 'https://api.telegram.org/bot' . $bot_token . '/sendMessage';
            $telegram_params = array( 'chat_id' => $group_id, 'text' => $message );

            wp_remote_post($telegram_url, array( 'method' => 'POST', 'timeout' => 45, 'headers' => array( 'Content-Type' => 'application/x-www-form-urlencoded' ), 'body' => $telegram_params ) );
        }
    }

    // Adding an item to your cart
    public static function hrcwtm_woo_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
        $add_cart = get_option( WpTelMessageSetting::ADD_CART );
        if ( $add_cart ) {
            $bloginfo = get_bloginfo( 'name' );
            $user_info = self::hrcwtm_get_current_user_info();
            $product = wc_get_product( $product_id );
            $product_name = $product->get_name();
            $product_price = $product->get_price();

            $message = __( "A new item has been added to the cart:", "wptelmessage" ) . "\n\n"
                . __( "Website:", "wptelmessage" ) . " " . $bloginfo . "\n"
                . __( "User:", "wptelmessage" ) . " " . $user_info['display_name'] . " (" . $user_info['username'] . ")\n"
                . __( "Product:", "wptelmessage" ) . " " . $product_name . "\n"
                . __( "Price:", "wptelmessage" ) . " " . $product_price . "\n"
                . __( "Quantity:", "wptelmessage" ) . " " . $quantity;

            self::hrcwtm_woo_send_to_telegram( $message );
        }
    }

    // Removing an item from the cart
    public static function hrcwtm_woo_remove_from_cart( $cart_item_key, $cart ) {
        $remove_cart = get_option( WpTelMessageSetting::REMOVE_CART );
        if ( $remove_cart ) {
            $bloginfo = get_bloginfo( 'name' );
            $user_info = self::hrcwtm_get_current_user_info();
            $removed_item = $cart->removed_cart_contents[$cart_item_key];
            $product = wc_get_product( $removed_item['product_id'] );
            $product_name = $product->get_name();
            $product_price = $product->get_price();

            $message = __( "The product has been removed from the cart:", "wptelmessage" ) . "\n\n"
                . __( "Website:", "wptelmessage" ) . "" . $bloginfo . "\n"
                . __( "User:", "wptelmessage" ) . " " . $user_info['display_name'] . " (" . $user_info['username'] . ")\n"
                . __( "Product:", "wptelmessage" ) . " "  . $product_name . "\n"
                . __( "Price:", "wptelmessage" ) . " " . $product_price . "\n"
                . __( "Quantity:", "wptelmessage" ) . " " . $removed_item['quantity'];

            self::hrcwtm_woo_send_to_telegram( $message );
        }
    }
    // Changing product status
    public static function hrcwtm_woo_order_status_changed($order_id, $old_status, $new_status, $order ) {
        $order_status_changed = get_option( WpTelMessageSetting::ORDER_STATUS_CHANGED );
        if ( $order_status_changed ) {

            $bloginfo = get_bloginfo( 'name' );
            $order = wc_get_order( $order_id);
            $order_number = $order->get_order_number();
            $order_total = $order->get_total();
            $order_status = $order->get_status();
            $payment_method = $order->get_payment_method();
            $user_info = self::hrcwtm_get_current_user_info();
            $billing_email = $order->get_billing_email();
            $customer_phone = $order->get_meta( '_billing_phone' );
            $order_date = $order->get_date_created()->format( 'd.m.Y H:i:s' );

            $message = __( "New order!", "wptelmessage" ) . "\n\n"
                . __( "Website:", "wptelmessage" ) . " " . $bloginfo . "\n"
                . __( "From", "wptelmessage" ) . " " . $old_status . " " .__( "to", "wptelmessage" )  . " " . $new_status . "\n"
                . __( "Order number:", "wptelmessage" ) . " " . $order_number . "\n"
                . __( "Order amount:", "wptelmessage" ) . " " . $order_total . "\n"
                . __( "Order status:", "wptelmessage" ) . " " . $order_status ."\n"
                . __( "Payment method:", "wptelmessage" ) . " " . $payment_method ."\n"
                . __( "User:", "wptelmessage" ) . " " . $user_info['display_name'] . " (" . $user_info['username'] . ")\n";
            if (!empty($billing_email)) {
                $message .= __( "Customer's email:", "wptelmessage" ) . " " . $billing_email . "\n";
            }

            if ( !empty($customer_phone ) ) {
                $message .= __( "Customer's phone:", "wptelmessage" ) . " " . $customer_phone . "\n";
            }
            $message .= __( "Order time:", "wptelmessage" ) . " " . $order_date . "\n";

            self::hrcwtm_woo_send_to_telegram( $message );
        }
    }

    // Completing an order
    public static function hrcwtm_woo_order_status_completed( $order_id ) {
        $order_status_completed = get_option( WpTelMessageSetting::ORDER_STATUS_COMPLETED );
        if ( $order_status_completed ) {
            $bloginfo = get_bloginfo( 'name' );
            $order = wc_get_order($order_id);
            $order_number = $order->get_order_number();
            $order_total = $order->get_total();
            $order_status = $order->get_status();
            $payment_method = $order->get_payment_method();
            $user_info = self::hrcwtm_get_current_user_info();
            $billing_email = $order->get_billing_email();
            $customer_phone = $order->get_meta('_billing_phone');
            $order_date = $order->get_date_created()->format( 'd.m.Y H:i:s' );

            $message = __( "Order has been successfully completed!", "wptelmessage" ) . "\n\n"
                . __( "Website:", "wptelmessage" ) . " " . $bloginfo . "\n"
                . __( "Order number:", "wptelmessage" ) . " " . $order_number . "\n"
                . __( "Order amount:", "wptelmessage" ) . " " . $order_total . "\n"
                . __( "Order status:", "wptelmessage" ) . " " . $order_status ."\n"
                . __( "Payment method:", "wptelmessage" ) . " " . $payment_method ."\n"
                . __( "User:", "wptelmessage" ) . " " . $user_info['display_name'] . " (" . $user_info['username'] . ")\n";
            if ( !empty($billing_email ) ) {
                $message .= __( "Customer's email:", "wptelmessage" ) . " " . $billing_email . "\n";
            }

            if ( !empty($customer_phone ) ) {
                $message .= __( "Customer's phone:", "wptelmessage" ) . " " . $customer_phone . "\n";
            }
            $message .= __( "Order time:", "wptelmessage" ) . " " . $order_date . "\n";

            self::hrcwtm_woo_send_to_telegram( $message );
        }
    }

    // Low stock of goods
    public static function hrcwtm_woo_low_stock_notification( $product_id ) {
        $low_stock_notification = get_option( WpTelMessageSetting::LOW_STOCK_NOTIFICATION );
        if ( $low_stock_notification ) {
            $bloginfo = get_bloginfo( 'name' );
            $id = $product_id->id;
            $product_name = $product_id->get_name();
            $stock_quantity = $product_id->get_stock_quantity();
            $edit_url = get_edit_post_link($product_id->get_id());
            $edit_url = home_url( 'wp-admin/post.php?post=' . $id . '&action=edit' );

            $message = __( "Low stock of products!", "wptelmessage" ) . "\n\n"
                . __( "On the website:", "wptelmessage" ) . " " . $bloginfo . "\n"
                . __( "Product:", "wptelmessage" ) . " " . $product_name . "\n"
                . __( "In stock:", "wptelmessage" ) . " " . $stock_quantity . "\n"
                . __( "Link to the product:", "wptelmessage" ) . " " . $edit_url . "\n";

            self::hrcwtm_woo_send_to_telegram( $message );
        }
    }
}
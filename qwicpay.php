<?php
/**
 * Plugin Name: QwicPay Checkout
 * Plugin URI: https://www.qwicpay.com/
 * Description: Adds a QwicPay instant checkout button to WooCommerce.
 * Version: 1.2.15
 * Author: QwicPay Pty Ltd
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: qwicpay-checkout
 * 
 */


/*
 * Copyright (C) 2025 QwicPay Pty Ltd
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'QWICPAY_CHECKOUT_MIN_PHP', '7.4' );


function qwicpay_checkout_check_requirements() {
    // Check PHP
    if ( version_compare( PHP_VERSION, QWICPAY_CHECKOUT_MIN_PHP, '<' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            echo esc_html__( 'QwicPay Checkout requires PHP version 7.4 or higher.', 'qwicpay-checkout' );
            echo '</p></div>';
        } );
        return false;
    }

    // Check WooCommerce
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p>';
            echo esc_html__( 'QwicPay Checkout requires WooCommerce 5.0 or higher.', 'qwicpay-checkout' );
            echo '</p></div>';
        } );
        return false;
    }

    return true;
}

if ( ! qwicpay_checkout_check_requirements() ) {
    return;
}

class QwicPayCheckout_Integration
{

    public function __construct() {
        add_filter( 'woocommerce_settings_tabs_array',   [ $this, 'add_settings_tab' ], 50 );
        add_action( 'woocommerce_settings_tabs_qwicpay', [ $this, 'settings_tab' ] );
        add_action( 'woocommerce_update_options_qwicpay', [ $this, 'update_settings' ] );
        add_action( 'init', [ $this, 'register_button_hook' ] ); // Dynamically selected hook based on user input
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'woocommerce_widget_shopping_cart_total', [ $this, 'output_qwicpay_button_half' ], 5 );

    }

    /**
     * Add QwicPay top-level menu and submenus to WP Admin.
     * @since 1.1.8
     */
   public function add_admin_menu() {

        add_menu_page(
            'QwicPay',
            'QwicPay',
            'manage_options',
            'qwicpay-main',                     
            '__return_false',                   
            plugin_dir_url( __FILE__ ) . 'assets/qwicpay-icon.png',
            56
        );

        add_submenu_page(
            'qwicpay-main',
            'Merchant Access Portal',
            'Merchant Access Portal',
            'manage_options',
            'qwicpay-portal',
            [ $this, 'render_portal_page' ]
        );

        add_submenu_page(
            'qwicpay-main',
            'Settings',
            'Settings',
            'manage_options',
            'qwicpay-settings',                // ← new slug
            [ $this, 'render_settings_redirect' ]
        );

        remove_submenu_page( 'qwicpay-main', 'qwicpay-main' );
    }



   /**
     * Redirects to WooCommerce > Settings > QwicPay tab.
     * @since 1.1.2
     */
    public function render_settings_redirect() {
        wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=qwicpay' ) );
        exit;
    }

    /**
     * Renders the Merchant Access Portal with iframe.
     * @since 1.1.2
     */
    public function render_portal_page() {
        echo '<div class="wrap"><h1>Merchant Access Portal</h1>';
        echo '<iframe src="https://map.qwicpay.com" width="100%" height="800px" style="border: none;"></iframe>';
        echo '</div>';
    }


    /**
     * Add QwicPay tab to WooCommerce Settings.
     * @since 1.0.0
     */
    public function add_settings_tab( $tabs ) {
        $tabs['qwicpay'] = __( 'QwicPay Settings', 'qwicpay-checkout' );
        return $tabs;
    }

    /**
     * Output settings fields.
     * @since 1.0.0
     */
public function settings_tab() {
    

    // Show settings fields
    woocommerce_admin_fields( $this->get_settings() );
    // Display dynamic "Link Status"
    $this->display_link_status();
    // Display Activate/Re-activate Button
    $this->display_activate_button();
}


    /**
     * Save settings fields.
     * @since 1.0.0
     */
    public function update_settings() {
        woocommerce_update_options( $this->get_settings() );
    }

    /**
     * Define all settings.
     * @since 1.0.0
     */
    private function get_settings() {
        return [
            [
                'name' => __( 'QwicPay Settings', 'qwicpay-checkout' ),
                'type' => 'title',
                'id'   => 'qwicpay_section_title',
            ],
            [
                'name'     => __( 'Hook Location', 'qwicpay-checkout' ),
                'desc'     => __( 'Where should the QwicPay button appear?', 'qwicpay-checkout' ),
                'id'       => 'qwicpay_hook_location',
                'type'     => 'select',
                'options'  => [
                    'woocommerce_cart_totals_after_order_total' => 'Cart Totals After Order Total',
                    'woocommerce_proceed_to_checkout'           => 'Proceed to Checkout Button',
                    'woocommerce_review_order_before_submit'    => 'Before Place Order Button',
                ],
                'default'  => 'woocommerce_cart_totals_after_order_total',
            ],
            [
                'name' => __( 'Merchant ID', 'qwicpay-checkout' ),
                'id'   => 'qwicpay_merchant_id',
                'type' => 'text',
                'desc' => __( 'Your QwicPay Merchant ID', 'qwicpay-checkout' ),
                'default' => '',
            ],
            [
                'name'    => __( 'Stage', 'qwicpay-checkout' ),
                'desc'     => __( 'In Test, no payment are accepted. Use with caution', 'qwicpay-checkout' ),
                'id'      => 'qwicpay_stage',
                'type'    => 'select',
                'options' => [
                    'test' => __( 'Test', 'qwicpay-checkout' ),
                    'PROD' => __( 'Production', 'qwicpay-checkout' ),
                ],
                'default' => 'test',
            ],
            [
                'name'    => __( 'Currency', 'qwicpay-checkout' ),
                'id'      => 'qwicpay_currency',
                'type'    => 'select',
                'options' => [
                    'ZAR' => 'ZAR',
                ],
                'default' => 'ZAR',
            ],
            [
                'name'    => __( 'Button Style', 'qwicpay-checkout' ),
                'desc'    => __( 'Select which QwicPay button image to use', 'qwicpay-checkout' ),
                'id'      => 'qwicpay_button_style',
                'type'    => 'select',
                'options' => [
                    plugins_url( 'assets/buttons/QwicPay+Button+BlueBGWhiteText.svg', __FILE__ ) => __( 'Blue Round', 'qwicpay-checkout' ),
                    plugins_url( 'assets/buttons/QwicPay+Button+BlueBGWhiteText+(Squared).svg', __FILE__ ) => __( 'Blue Square', 'qwicpay-checkout' ),
                    plugins_url( 'assets/buttons/QwicPay+Button+WhiteBGBlueText.svg', __FILE__ ) => __( 'White Round', 'qwicpay-checkout' ),
                    plugins_url( 'assets/buttons/QwicPay+Button+WhiteBGBlueText+(Squared).svg', __FILE__ ) => __( 'White Square', 'qwicpay-checkout' ),
                ],
                'default' => plugins_url( 'assets/buttons/QwicPay+Button+BlueBGWhiteText.svg', __FILE__ ),
            ],
            [
                'type' => 'sectionend',
                'id'   => 'qwicpay_section_end',
            ],
        ];
}



    /**
     * Reads the chosen hook from settings and registers our display callback there.
     * @since 1.0.0
     */
    public function register_button_hook() {
        $hook = get_option( 'qwicpay_hook_location', 'woocommerce_cart_totals_after_order_total' );
        add_action( $hook, [ $this, 'output_qwicpay_button_full' ], 5 );
    }

    /**
     * @since 1.2.2
     * Full-width button (100%) for normal cart/checkout hooks.
     */
    public function output_qwicpay_button_full() {
        $this->render_qwicpay_button( '100%' );
    }

    /**
     * @since 1.2.2
     * Half-width button (50%) for the mini-cart widget.
     */
    public function output_qwicpay_button_half() {
        $this->render_qwicpay_button( '50%' );
    }


    /**
     * Shared renderer for the QwicPay button.
     * @since 1.2.2
     * @param string $width CSS width (e.g. '100%' or '50%')
     */
    private function render_qwicpay_button( $width ) {
        // fetch settings
        $merchant_id   = sanitize_text_field( get_option( 'qwicpay_merchant_id', '' ) );
        $stage         = get_option( 'qwicpay_stage', 'test' );
        $currency      = get_option( 'qwicpay_currency', 'ZAR' );
        $button_url    = esc_url( get_option( 'qwicpay_button_style' ) );

        if ( empty( $merchant_id ) ) {
            return; // nothing to do
        }

        // check QwicPay uptime
        $response = wp_remote_get( "https://ice.qwicpay.com/isup/{$merchant_id}", [ 'timeout' => 5 ] );
        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return;
        }

        // prepare cart data
        $note      = ''; // adjust if you have a cart-wide note
        $coupons   = WC()->cart->get_applied_coupons();
        $promocode = ! empty( $coupons ) ? array_shift( $coupons ) : '';
        $products  = [];

        foreach ( WC()->cart->get_cart() as $item ) {
            $qty = intval( $item['quantity'] );
            for ( $i = 0; $i < $qty; $i++ ) {
                $products[] = [
                    'product_id'   => $item['product_id'],
                    'variation_id' => ! empty( $item['variation_id'] ) ? $item['variation_id'] : 0,
                ];
            }
        }

        $products_json = urlencode( wp_json_encode( $products ) );

        // build checkout URL
        $checkout_url = add_query_arg( [
            'merchantid' => $merchant_id,
            'stage'      => $stage,
            'note'       => $note,
            'promocode'  => $promocode,
            'currency'   => $currency,
            'products'   => $products_json,
        ], 'https://ice.qwicpay.com/app/woo/checkout/' );


        echo '  <a href="' . esc_url( $checkout_url ) . '" target="_blank" class="qwicpay-checkout-button" style="width:' . esc_attr( $width ) . ';">';
        echo '    <img src="' . $button_url . '" alt="QwicPay Checkout Button" style="width:100%; height:auto;">';
        echo '  </a>';

    }


    

    /**
     * Display the merchant activation status.
     * @since 1.1.2
     */
    private function display_link_status() {
        $merchant_id = sanitize_text_field( get_option( 'qwicpay_merchant_id', '' ) );

        $status = 'Not Activated';
        if ( ! empty( $merchant_id ) ) {
            $response = wp_remote_get( "https://ice.qwicpay.com/app/woo/status/{$merchant_id}", [ 'timeout' => 5 ] );
            if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
                $status = '<span style="color: green; font-weight: bold;">Activated</span>';
            } else {
                $status = '<span style="color: red; font-weight: bold;">Not Activated</span>';
            }
        }

        echo '<h2>QwicPay Link Status</h2>';
        echo 'Status: ' . $status . '<br><br>';
    }

    /**
     * Display the Activate/Re-Activate button.
     * @since 1.1.2
     */
    private function display_activate_button() {
        $merchant_id = sanitize_text_field( get_option( 'qwicpay_merchant_id', '' ) );
        $link_url = "https://ice.qwicpay.com/app/woo/link/{$merchant_id}";

        echo '<a class="button button-primary" href="' . esc_url( $link_url ) . '" target="_blank">Activate / Re-Activate</a>';
    }

}

new QwicPayCheckout_Integration();

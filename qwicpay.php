<?php
/**
 * Plugin Name: QwicPay Integration for WooCommerce
 * Description: Adds a QwicPay instant checkout button to cart/checkout pages, with configurable hook, merchant ID, stage and currency.
 * Version:     1.0.0
 * Author:      Enrico Leigh
 * Text Domain: qwicpay
 * 
 * This code is the intellectual property of QwicPay Pty Ltd (Registration No. K2024202050).
 * All rights reserved.
 * 
 * Unauthorized copying, distribution, modification, or use of this code, in whole or in part,
 * without express written permission from QwicPay Pty Ltd is strictly prohibited.
 * 
 * This code is provided solely for use in connection with the QwicPay Instant Checkout Ecosystem (ICE).
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WC_QwicPay_Integration {

    public function __construct() {
        add_filter( 'woocommerce_settings_tabs_array',   [ $this, 'add_settings_tab' ], 50 );
        add_action( 'woocommerce_settings_tabs_qwicpay', [ $this, 'settings_tab' ] );
        add_action( 'woocommerce_update_options_qwicpay', [ $this, 'update_settings' ] );
        add_action( 'init', [ $this, 'register_button_hook' ] ); //Dynamically selected hook based on user input
    }

    /**
     * Add QwicPay tab to WooCommerce Settings.
     * @since 1.0.0
     */
    public function add_settings_tab( $tabs ) {
        $tabs['qwicpay'] = __( 'QwicPay Settings', 'qwicpay' );
        return $tabs;
    }

    /**
     * Output settings fields.
     * @since 1.0.0
     */
    public function settings_tab() {
        woocommerce_admin_fields( $this->get_settings() );
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
                'name' => __( 'QwicPay Settings', 'qwicpay' ),
                'type' => 'title',
                'id'   => 'qwicpay_section_title',
            ],
            [
                'name'     => __( 'Hook Location', 'qwicpay' ),
                'desc'     => __( 'Where should the QwicPay button appear?', 'qwicpay' ),
                'id'       => 'qwicpay_hook_location',
                'type'     => 'select',
                'options'  => [
                    'woocommerce_cart_totals_after_order_total' => 'Cart Totals After Order Total',
                    'woocommerce_proceed_to_checkout'           => 'Proceed to Checkout Button',
                    'woocommerce_review_order_before_submit'    => 'Before Place Order Button',
                    'woocommerce_after_checkout_form'           => 'After Checkout Form',
                ],
                'default'  => 'woocommerce_cart_totals_after_order_total',
            ],
            [
                'name' => __( 'Merchant ID', 'qwicpay' ),
                'id'   => 'qwicpay_merchant_id',
                'type' => 'text',
                'desc' => __( 'Your QwicPay Merchant ID', 'qwicpay' ),
                'default' => '',
            ],
            [
                'name'    => __( 'Stage', 'qwicpay' ),
                'id'      => 'qwicpay_stage',
                'type'    => 'select',
                'options' => [
                    'test' => __( 'Test', 'qwicpay' ),
                    'PROD' => __( 'Production', 'qwicpay' ),
                ],
                'default' => 'test',
            ],
            [
                'name'    => __( 'Currency', 'qwicpay' ),
                'id'      => 'qwicpay_currency',
                'type'    => 'select',
                'options' => [
                    'ZAR' => 'ZAR',
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                    'GBP' => 'GBP',
                ],
                'default' => 'ZAR',
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
        add_action( $hook, [ $this, 'output_qwicpay_button' ], 5 );
    }

    /**
     * Renders the QwicPay checkout button.
     * @since 1.0.0
     */
    public function output_qwicpay_button() {
        // fetch settings
        $merchant_id = sanitize_text_field( get_option( 'qwicpay_merchant_id', '' ) );
        $stage       = get_option( 'qwicpay_stage', 'test' );
        $currency    = get_option( 'qwicpay_currency', 'ZAR' );

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

        $button_url = 'https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText.svg';

        echo '<a href="' . esc_url( $checkout_url ) . '" target="_blank" class="qwicpay-checkout-button">';
        echo '<img src="' . esc_url( $button_url ) . '" alt="QwicPay Checkout Button">';
        echo '</a>';
    }
}

new WC_QwicPay_Integration();

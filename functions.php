/**
 * QwicPay Integration Code
 * 
 * This code is the intellectual property of QwicPay Pty Ltd (Registration No. K2024202050).
 * All rights reserved.
 * 
 * Unauthorized copying, distribution, modification, or use of this code, in whole or in part,
 * without express written permission from QwicPay Pty Ltd is strictly prohibited.
 * 
 * This code is provided solely for use in connection with the QwicPay Instant Checkout Ecosystem (ICE).
 */

//Place this Code in the functions.php Theme File of your Theme File Editor on WordPress

//NOTE: The 'QwicPay Checkout Faster' Button must be placed above any 'Proceed to Checkout'/'Checkout' buttons on the store. 
//NOTE: edit Within the CONFIG section

//If You have any questions, please contact QwicPay. 

add_action( 'woocommerce_cart_totals_after_order_total', 'add_qwicpay_checkout_button', 5 );

function add_qwicpay_checkout_button() {
	
	//START CONFIG
	
    // Querying the merchant ID, stage, note, promocode, and currency
    $merchantid = '0'; //Replace with merchant id  
    $stage = 'test'; // Replace with 'test' or 'PROD' as per phase. Only production endabled stores may use PROD
    $note = isset( WC()->cart->get_cart()->note ) ? WC()->cart->get_cart()->note : ''; // Cart note
    $promocode = WC()->cart->get_applied_coupons() ? WC()->cart->get_applied_coupons()[0] : ''; // Applied promo code at cart
    $currency = 'ZAR';
	
	// Choose one of the 4 available buttons below.
	// Copy and paste the URL of your preferred button into the "button_url" field. 
	// 1. QwicPay Round Button with Blue Background: https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText.svg
	// 2. QwicPay Square Button with Blue Background: https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText+(Squared).svg
	// 3. QwicPay Round Button with White Background: https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText.svg
	// 4. QwicPay Square Button with White Background: https://cdn.qwicpay.com/Buttons/QwicPay+Button+WhiteBGBlueText+(Squared).svg

    $button_url = 'https://cdn.qwicpay.com/Buttons/QwicPay+Button+BlueBGWhiteText.svg';
	
	//END CONFIG
    
	//ensure uptime
	$response = wp_remote_get( "https://ice.qwicpay.com/isup/$merchantid", [ 'timeout' => 5 ] );
    if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
        return;
    }
	
    $products = [];
    foreach ( WC()->cart->get_cart() as $cart_item ) {
    $quantity = isset( $cart_item['quantity'] ) ? intval( $cart_item['quantity'] ) : 1;
    
    for ( $i = 0; $i < $quantity; $i++ ) {
        $products[] = [
            'product_id'   => $cart_item['product_id'],
            'variation_id' => isset( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : 0
        ];
    }
}
    $products_json = json_encode( $products );
	
		
	
    $checkout_url = "https://ice.qwicpay.com/app/woo/checkout/?merchantid=$merchantid&stage=$stage&note=" . urlencode($note) . "&promocode=" . urlencode($promocode) . "&currency=$currency&products=" . urlencode($products_json);
    echo '<a href="' . esc_url( $checkout_url ) . '" target="_blank" class="qwicpay-checkout-button">
            <img src="' . esc_url( $button_url ) . '" alt="QwicPay Checkout Button" />
          </a>';
}

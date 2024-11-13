<?php

function after_cart_help_section() {
	
	hfc_section('need-help', 'template-parts/knowledge-base/home/more-help' );
	
}

add_action( 'woocommerce_after_cart', 'after_cart_help_section', 10 );

 /**
 * @snippet       Display Weight @ Cart & Checkout - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WC 3.9
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
//add_action( 'woocommerce_before_checkout_form', 'bbloomer_print_cart_weight' );
//add_action( 'woocommerce_before_cart', 'bbloomer_print_cart_weight' );
  
function bbloomer_print_cart_weight() {
	$total_weight = get_cart_weight();
	$custom_products_weight = get_custom_products_cart_weight();
	$standard_weight = $total_weight - $custom_products_weight;
	
   	$notice = 'Your total cart weight is: ' . $total_weight . get_option( 'woocommerce_weight_unit' );
	$notice .= '<br>Your custom products cart weight is: ' . $custom_products_weight . get_option( 'woocommerce_weight_unit' );
	$notice .= '<br>Your standard products cart weight is: ' . $standard_weight . get_option( 'woocommerce_weight_unit' );
   	wc_print_notice( $notice, 'notice' );
}

if(current_user_can('administrator')){
    
// 	add_action( 'woocommerce_before_checkout_form', 'cart_volume_test' );
// 	add_action( 'woocommerce_before_cart', 'cart_volume_test' );
	
}

	
function cart_volume_test() {
	$notice = __('Cart volume') . ': ' . get_cart_volume() . ' cm³';
	
	wc_print_notice( $notice, 'notice' );
}


// add_action( 'woocommerce_before_checkout_form', 'show_shipping_rules' );
// add_action( 'woocommerce_before_cart', 'show_shipping_rules' );

function show_shipping_rules() {
	$notice = '<ul>
		<li>1kg DPD Bag: 4,650cm³ | £5.99</li>
		<li>5kg DPD Bag: 16,770cm³ | £8.99</li>
		<li>10kg DPD Parcel: 1m max | £14.50 + £0.5 per kg</li>
		<li>10kg DPD Parcel: Between 1m and 1.5m | £19.50 + £0.5 per kg</li>
		<li>DX: 0kg - 100kg & 3m - 3m | £49.95 + £0.55 per kg</li>
		<li>Palletways: 100kg - 400kg & 1,200,000cm³ | £65.00</li>
		<li>Palletways (Oversized): 100kg - 400kg & 2,400,000cm³ | £95.00</li>
	</ul>';
	
	wc_print_notice( $notice, 'notice' );
}

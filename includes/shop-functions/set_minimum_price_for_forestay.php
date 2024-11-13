<?php
// Update Custom Forestay Price to £25 if it is less that that
add_action( 'woocommerce_before_calculate_totals', 'update_forestay_price_in_cart', 10, 1 );
function update_forestay_price_in_cart( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    
    // Set the product ID you want to check
    $target_product_id = 524919;
	$minimum_price = 25;
    
    foreach ( $cart->get_cart_contents() as $cart_item_key => $cart_item ) {
        if ( $cart_item['product_id'] == $target_product_id ) {
            // Check if the line total is less than the minimum price
            if ( $cart_item['line_total'] < $minimum_price ) {
                WC()->cart->add_fee( 'Forestay Surcharge', 25 - $cart_item['line_total'], true, '' );
            }
        }
    }

}
?>
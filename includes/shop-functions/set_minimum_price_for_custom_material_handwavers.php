<?php
// Update Custom Handwavers Price to £25 if it is less than that
add_action( 'woocommerce_before_calculate_totals', 'update_custom_handwavers_prices_in_cart', 10, 1 );
function update_custom_handwavers_prices_in_cart( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    
    $target_product_id = 524957;
    $minimum_price = 25;

    $total_price = 0;
    
    $product_exists = false;
    
    foreach ( $cart->get_cart_contents() as $cart_item_key => $cart_item ) {
        if ( $cart_item['product_id'] == $target_product_id ) {
            $product_exists = true; // Product exists in the cart
			
            // Calculate the line total for the item
            $line_total = $cart_item['line_total'];
            
            // Update the total price for the products
            $total_price += $line_total;
        }
    }
    

    if ( $product_exists && $total_price < $minimum_price ) {
        WC()->cart->add_fee( 'Handwaver Surcharge', $minimum_price - $total_price, true, '' );
    }
}

?>
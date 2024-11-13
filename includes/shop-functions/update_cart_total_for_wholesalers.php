<?php
add_action( 'woocommerce_before_calculate_totals', 'update_cart_total_if_wholesaler', 99999999999999999 );
function update_cart_total_if_wholesaler( $cart ) {
    if ( is_user_logged_in() ) {
        // Check if user has the 'wholesale' role
        if ( current_user_can( 'wcwp_wholesale' ) ) {
            // Loop through cart items
            foreach( $cart->get_cart() as $cart_item_key => $cart_item ) {
                if ( $cart_item['_gform_total'] ) {
                    $addon_total = $cart_item['_gform_total'];
                    $product = $cart_item['data'];
                    $old_price = $product->get_price();
					
                    $new_price = $old_price + $addon_total;
                    $product->set_price( $new_price );
                }
            }
        }
    }
}

?>
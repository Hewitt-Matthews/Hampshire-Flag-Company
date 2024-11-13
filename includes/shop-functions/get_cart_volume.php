<?php
function get_cart_volume(){
    // Initializing variables
    $volume = $rate = 0;

    // Get the dimension unit set in Woocommerce
    $dimension_unit = get_option( 'woocommerce_dimension_unit' );
    
    // Calculate the rate to be applied for volume in m3
    if ( $dimension_unit == 'mm' ) {
        $rate = pow(10, 9);
    } elseif ( $dimension_unit == 'cm' ) {
        $rate = pow(10, 6);
    } elseif ( $dimension_unit == 'm' ) {
        $rate = 1;
    }

    if( $rate == 0 ) return false; // Exit

    // Loop through cart items
    foreach(WC()->cart->get_cart() as $cart_item) {
        //echo "<pre>Cart Item:" . print_r($cart_item, true) . "</pre>";
        if($cart_item['addons']) {
            $volume += $cart_item['volume'] * $cart_item['quantity'];
        } elseif ($cart_item['_gravity_form_lead'] && $cart_item['volume']) {
            $volume += $cart_item['volume'] * $cart_item['quantity'];
        } else {
            // Get an instance of the WC_Product object and cart quantity
            $product = $cart_item['data'];
            $qty     = $cart_item['quantity'];
            
            // Get product dimensions
            $length = get_post_meta($product->get_id(), '_custom_length', true);
            if(empty($length)) { // Fallback to original dimensions
                $length = $product->get_length();
            }

            $width = get_post_meta($product->get_id(), '_custom_width', true);
            if(empty($width)) { // Fallback to original dimensions
                $width = $product->get_width();
            }

            $height = get_post_meta($product->get_id(), '_custom_depth', true);
            if(empty($height)) { // Fallback to original dimensions
                $height = $product->get_height();
            }
            
            // Calculations at item level
            $volume += $length * $width * $height * $qty;
        }

    } 
    return ($volume / $rate) * 1000000;
}
?>

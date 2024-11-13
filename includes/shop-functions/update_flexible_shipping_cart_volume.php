<?php

// Define the function to update the cart volume
function update_shipping_cart_volume( ) {
    if (function_exists('get_cart_volume')) {
        $cart_volume = get_cart_volume(); // Get the cart volume using your existing function
        return $cart_volume; // Return the updated cart volume
    }
    return $volume; // Return the original volume if get_cart_volume() function doesn't exist
}

// Hook the function to the flexible-shipping/condition/contents_volume hook
add_filter( 'flexible-shipping/condition/contents_volume', 'update_shipping_cart_volume', 10, 2 );

?>
<?php

// Define the function to update the cart weight
function update_shipping_cart_weight($data) {

	$total_weight = get_cart_weight();

	return $total_weight;
	
}

// Hook the function to the flexible-shipping/condition/dimensional_contents_weight hook
add_filter( 'flexible-shipping/condition/contents_weight', 'update_shipping_cart_weight', 10, 2 );


?>
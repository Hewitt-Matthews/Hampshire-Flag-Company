<?php
function get_custom_products_cart_weight(){
    // Initializing variables
    $weight = 0;

    // Loop through cart items
    foreach(WC()->cart->get_cart() as $cart_item) {
		
		if($cart_item['addons']) {
			$weight += $cart_item['weight'] * $cart_item['quantity'];
		} elseif ($cart_item['_gravity_form_lead']) {
			$weight += $cart_item['weight'] * $cart_item['quantity'];
		}

    } 
    return $weight;
}
?>
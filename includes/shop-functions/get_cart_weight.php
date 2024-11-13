<?php
function get_cart_weight(){
    // Initializing variables
    $weight = 0;

    // Loop through cart items
    foreach(WC()->cart->get_cart() as $cart_item) {
		
		//echo "<pre>Cart Item:" . print_r($cart_item, true) . "</pre>";
		
		if($cart_item['addons']) {
			$weight += $cart_item['weight'] * $cart_item['quantity'];
		} elseif ($cart_item['_gravity_form_lead'] && $cart_item['weight']) {
			$weight += $cart_item['weight'] * $cart_item['quantity'];
		} else {
			// Get an instance of the WC_Product object and cart quantity
			$product = $cart_item['data'];
			$qty     = $cart_item['quantity'];
			// Get product dimensions  
			$item_weight = $product->get_weight();
			
			 // Calculations a item level
       		$weight += $item_weight * $qty;
		}

    } 
    return $weight;
}
?>
<?php

function get_discount_amount($discounts, $quantity, $item_price) {
    $discount_amount = 0;

    // Sort the discount keys in ascending order
    $discount_keys = array_keys($discounts);
    sort($discount_keys, SORT_NUMERIC);
    // Find the discount based on the quantity
    foreach ($discount_keys as $discount_key) {
        $range = explode(' - ', $discount_key);
        
		$min_qty = intval($range[0]);
        $max_qty = intval($range[1]);
		
		if ($quantity >= $min_qty && $quantity <= $max_qty) {
			$discount = $discounts[$discount_key];
			
			if (strpos($discount, '%') !== false) {
				// Discount is a percentage
				$discount_percentage = intval(trim($discount, '%'));
				if ($discount_percentage > 0 && $quantity > 0) {					
					$discount_amount = $item_price * ($discount_percentage / 100);
				}
			} else {
				// Discount is a fixed amount
				$discount_amount = floatval($discount);
			}
			break;
		}
    }

    return $discount_amount;
}

function roundUp($price) {
    $rounded = ceil($price * 100) / 100;
    return ($rounded == floor($price)) ? $rounded + 0.01 : $rounded;
}


function apply_discounts($cart) {
    // Load the discounts array from a separate file
    require 'discounts-array.php';
    
	$total_discounts = 0;
	
    // Loop through the cart items
	foreach ($cart as $cart_item) {
		$product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];

        $discount_applied = false;

        // Check for product-specific discounts first
        if (isset($discount_array['products'][$product_id])) {
            $product_discount = $discount_array['products'][$product_id];
			
			foreach ($product_discount as $range => $discount) {
				$range_limits = explode('-', $range);
				$range_min = trim($range_limits[0]);
				$range_max = trim($range_limits[1]);

				
				if ($quantity >= $range_min && $quantity <= $range_max) {
					$discount_amount = get_discount_amount($product_discount, $quantity, $cart_item['data']->get_price());
					$new_price = $cart_item['data']->get_price() - $discount_amount;
					$total_discounts += $discount_amount * $quantity;

					// Now round up these values
					$new_price = roundUp($new_price);
					$total_discounts = roundUp($total_discounts);

					$cart_item['data']->set_price($new_price);
					$discount_applied = true;
					break 2;
				}
				
			}
			
        }

        // Check for category-specific discounts if product discount was not applied
        if (!$discount_applied) {
            $product_category_slugs = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
            foreach ($product_category_slugs as $product_category_slug) {
                if (isset($discount_array['categories'][$product_category_slug])) {
					//echo "<pre>" . print_r($product_category_slug, true) . "</pre>";
                    $category_discounts = $discount_array['categories'][$product_category_slug];
					
                    foreach ($category_discounts as $range => $discount) {
                        $range_limits = explode('-', $range);
                        $range_min = trim($range_limits[0]);
                        $range_max = trim($range_limits[1]);
						
                        if ($quantity >= $range_min && $quantity <= $range_max) {
							$old_price = $cart_item['data']->get_price();
                            $new_price = $cart_item['data']->get_price() - get_discount_amount($category_discounts, $quantity, $cart_item['data']->get_price());
                            $cart_item['data']->set_price($new_price);
							$total_discounts += ($old_price * $quantity) - ($new_price * $quantity);
                            $discount_applied = true;
                            break 2;
                        }
                    }
                }
            }
        }
    }
	
	if($total_discounts) {
		$discount_notice = "Great news! You've received £" . number_format(roundUp($total_discounts), 2) . " discount from this order!";
		if ( !is_checkout() ) {
			wc_print_notice( $discount_notice, 'notice' );
		}
  		
	}
	
}

// Run the above fucntion
add_action('woocommerce_before_calculate_totals', 'apply_custom_discounts');
//add_action('woocommerce_after_cart_item_quantity_update', 'apply_custom_discounts');

function apply_custom_discounts($cart) {
    // Get the cart object
    $cart = WC()->cart->get_cart();

    // Apply discounts to the cart
    apply_discounts($cart);
}

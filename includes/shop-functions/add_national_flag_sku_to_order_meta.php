<?php

// ADD SKU product meta to order information
add_action('woocommerce_thankyou', 'add_national_flag_sku_to_order_meta', 10, 1);

function add_national_flag_sku_to_order_meta($order_id) {
    
    // Get the order object
    $order = wc_get_order($order_id);
    
    // Loop through all the products in the order
    foreach ($order->get_items() as $item_id => $item) {
		
		$terms_to_check = [
			'standard-national-flags',
			'pride-flags'
		];
		
        // Check if the product belongs to the "national-flags" category
        if (is_product_in_category_or_parent($item->get_product_id(), $terms_to_check)) {
			
			if ($order->get_item_meta($item_id, 'Partcode', true)) {
				continue; // Partcode already added, skip to next item
			}
			
			$meta_array = [];
			
			foreach ($item->get_meta_data() as $meta) {
				$meta_key = $meta->key;
				$meta_value = $meta->value;
				// Use regular expression to remove any price in brackets from the meta key
				$clean_key = preg_replace('/\s*\([^)]+\)$/', '', $meta_key);
				$meta_array[$clean_key] = $meta_value;
			}
			
			unset($meta_array['_gravity_forms_history']);
			unset($meta_array['45 Degree Angle Cost']);
		
			$product = wc_get_product( $item->get_product_id() );
			
            // Get the product add-on options
            $sku = substr($product->get_sku(), 2);
            $material = $meta_array['Material'];
			$material = trim(preg_replace('/\([^£]+\)/', '', $material));
            $size = $meta_array['Size'];
			// Find the position of the first "("
			$startPos = strpos($size, '(');

			// Iterate through each set of brackets
			while ($startPos !== false) {
				// Find the position of the corresponding ")"
				$endPos = strpos($size, ')', $startPos);

				// Extract the substring between the brackets
				$substring = substr($size, $startPos, $endPos - $startPos + 1);

				// Check if the substring contains "." symbol, indicated it's a price
				if (strpos($substring, '.') !== false) {
					// Remove the substring from the original string
					$size = trim(str_replace($substring, '', $size));
				}

				// Find the position of the next "("
				$startPos = strpos($size, '(', $endPos);
			}

			$shape = $meta_array['Shape'];
            $finishing = $meta_array['Finishing'];
			$anti_fray = $meta_array['Add anti-fray netting?'];
			
 			//echo "<pre>Info:" . print_r($sku, true) . "</pre>";
            
            // Query the custom DB table for a matching SKU
            global $wpdb;
            $table_name = 'national_flag_skus';
			$pattern = '^[A-Z]{2}' . $wpdb->esc_like($sku) . '[0-9][A-Za-z0-9]*';
// 			$query = $wpdb->prepare("
// 			SELECT `Part-Code` FROM $table_name
// 			WHERE Material = %s AND Size = %s AND Shape = %s AND Finishing = %s AND `Anti-Fray` = %s AND SKU REGEXP %s", $material, $size, $shape, $finishing, $anti_fray, '^[A-Z]{2}' . $sku . '[0-9][A-Za-z0-9]*');
// 			
			if ($anti_fray == "Yes") {
				$anti_fray_skus = $wpdb->prepare("
					SELECT `Part-Code` FROM $table_name
					WHERE Material = %s AND Size = %s AND Shape = %s AND Finishing = %s AND `Anti-Fray` = %s AND SKU REGEXP %s", $material, html_entity_decode($size), $shape, $finishing, $anti_fray, '^[A-Z]{2}' . $sku . '[0-9][A-Za-z0-9]*');

				$anti_fray_results = $wpdb->get_results($anti_fray_skus);

				if (!$anti_fray_results) {
					$query = $wpdb->prepare("
						SELECT `Part-Code` FROM $table_name
						WHERE Material = %s AND Size = %s AND SKU REGEXP %s", $material, html_entity_decode($size), '^[A-Z]{2}' . $sku . '[0-9][A-Za-z0-9]*');
				} else {
					$query = $anti_fray_skus;
				}
			} else {
				$query = $wpdb->prepare("
					SELECT `Part-Code` FROM $table_name
					WHERE Material = %s AND Size = %s AND SKU REGEXP '{$pattern}'", 
					$material, html_entity_decode($size)
				);
			}

			$part_code = $wpdb->get_var($query);
			
//   			echo "<pre>query:" . print_r($query), true) . "</pre>";
			
            // Add the SKU to the product order meta
            if ($part_code) {
                wc_add_order_item_meta($item_id, 'Partcode', $part_code);
            } else {
				wc_add_order_item_meta($item_id, 'Partcode', "AUTOORDER");
			}
        }
    }
    
    // Save the order
    $order->save();
}

?>
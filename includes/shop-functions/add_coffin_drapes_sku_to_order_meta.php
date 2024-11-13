<?php
// ADD SKU product meta to order information
add_action('woocommerce_thankyou', 'add_coffin_drapes_sku_to_order_meta', 10, 1);
function add_coffin_drapes_sku_to_order_meta($order_id) {
    
    // Get the order object
    $order = wc_get_order($order_id);
    
    // Loop through all the products in the order
    foreach ($order->get_items() as $item_id => $item) {
		
		$terms_to_check = [
			'national-coffin-drape-flags'
		];
        
        // Check if the product belongs to the "national-flags" category
        if (has_term($terms_to_check, 'product_cat', $item->get_product_id())) {
			
			if ($order->get_item_meta($item_id, 'Partcode', true)) {
				//continue; // Partcode already added, skip to next item
			}

			$meta_array = [];
			
			foreach ($item->get_meta_data() as $meta) {
				$meta_key = $meta->key;
				$meta_value = $meta->value;
				// Use regular expression to remove any price in brackets from the meta key
				$clean_key = preg_replace('/\s*\([^)]+\)$/', '', $meta_key);
				$clean_val = preg_replace('/\s*\([^)]+\)$/', '', $meta_value);
				$meta_array[$clean_key] = $meta_value;
			}
			$product = wc_get_product( $item->get_product_id() );
			
            // Get the product add-on options
            $sku = substr($product->get_sku(), 2);
			$flag = $meta_array['Flag'];
			$fringe = $meta_array['Add Fringe?'];
            $finishing = $meta_array['Finishing'];
			
 			//echo "<pre>Meta:" . print_r($meta_array, true) . "</pre>";
            
            // Query the custom DB table for a matching SKU
            global $wpdb;
            $table_name = 'coffin_drapes_skus';
			$query = $wpdb->prepare("SELECT `Part_Code` FROM $table_name WHERE Flag = %s", $flag);

			$part_code = $wpdb->get_var($query);
			
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
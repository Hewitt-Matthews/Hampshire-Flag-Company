<?php

add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_data', 10, 3 );
function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
    $product = wc_get_product( $product_id );
    $volume = 0;
	$weight = 0;
    $size = '';
	$form_id = $cart_item_data['_gravity_form_lead']['form_id'];
	$custom_product_form_ids = [21, 22, 23, 24, 25, 26, 27, 31, 37];
	
    if ( ! empty( $cart_item_data ) && isset( $cart_item_data['addons'] ) ) {
        foreach ( $cart_item_data['addons'] as $addon ) {
            if ( 'Size' === $addon['name'] ) {
                $size = $addon['value'];
                break;
            }
        }
    } 
	
	if ( ! empty( $cart_item_data ) && isset( $cart_item_data['_gravity_form_lead'] ) ) {
		
		$cm_to_sqm_divisor = 10000;
		$flag_weight_grams_per_sqm = 0.115;
		
		//Check if custom product form is used
		if(in_array($form_id, $custom_product_form_ids)) {
			
			if($form_id == 21) {
				//Custom Burgees info
				$volume = $cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] * 0.3;
				$weight = ($cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
			}
			
			if($form_id == 22) {
				//Custom Windsocks
				
				$measurements = [];
				$user_selected_sizes = $cart_item_data['_gravity_form_lead']['1'];
				$measurementList = explode('|', $user_selected_sizes);
				array_pop($measurementList);
				$parts = explode(' ', $measurementList[0]);
				
				foreach ($parts as $part) {

					if(str_contains($part, 'cm')){
						$dimension = str_replace('cm', '', $part);
						array_push($measurements, $dimension);
					}
					
				}
		
				$volume = $measurements[0] * $measurements[2] * 0.3;
				$weight = ($measurements[0] * $measurements[2] / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
	
			}
			
			if($form_id == 23) {
				//Custom Forestay info
				$volume = $cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] * 0.3;
				$weight = ($cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
			}
			
			if($form_id == 24) {
				//Custom Car Flags info
				$volume = 40 * 30 * 0.3;
				$weight = (40 * 30 / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
			}
			
			if($form_id == 25) {
				//Custom Bunting info
				
				$material = $cart_item_data['_gravity_form_lead']['8'];
				$size = $cart_item_data['_gravity_form_lead']['7'];
				
				$ten_metre_volume_array = [
					"Knitted Polyester" => 2270,
					"Recycled Knitted Polyester" => 2270,
					"PVC" => 3740,
					"PVC Free" => 3740,
					"Paper" => 3740
				];
				
				$ten_metre_volume = $ten_metre_volume_array[$material];
				
				if($size == "5m (12 pennants)") {
					$volume = $ten_metre_volume / 2;
				}
				
				if($size == "10m (24 pennants)") {
					$volume = $ten_metre_volume;
				}
				
				if($size == "20m (48 pennants)") {
					$volume = $ten_metre_volume * 2;
				}
				
				$weight = (20 * 30 / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
			}
			
			if($form_id == 26) {
				//Custom Printed Flags info
				$volume = $cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] * 0.11;
				$weight = ($cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
			}
			
			if($form_id == 27) {
				//Custom Banners
				$volume = $cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] * 0.11;
				$weight = ($cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
			}
			
			if($form_id == 31) {
				//Custom sq/m handwavers
				$volume = $cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] * 0.11;
				$weight = ($cart_item_data['_gravity_form_lead']['3'] * $cart_item_data['_gravity_form_lead']['1'] / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
			}
			
			if($form_id == 37) {
				//Custom standard handwavers size info
				
				$material = $cart_item_data['_gravity_form_lead']['3'];
				$qty = $cart_item_data['_gravity_form_lead']['4'];
				
				if($material == "Paper Handwaver / Plastic Stick" || $material == "Paper Handwaver / Paper Stick") {
					$volume = 27 * 12.5 * 0.6;
					
					if($material == "Paper Handwaver / Paper Stick" && $qty >= 500) {
						//Trigger the £14.50 for over 500 paper flags
						$volume = 20000 / $qty;
					}
					
				} else {
					$volume = 40 * 2 * 2;
				}

				$weight = (21 * 15 / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
			}
			
			//echo '<pre>' . print_r($cart_item_data, true) . '</pre>';
			
		} else if($form_id == 41) {
			//Coloured Bunting Logic
			
			$material = str_replace("|0", "", $cart_item_data['_gravity_form_lead']['1']);
			$size = str_replace("|0", "", $cart_item_data['_gravity_form_lead']['3']);

			$ten_metre_volume_array = [
				"Knitted Polyester" => 2270,
				"Paper" => 3740,
				"Heavy Duty PVC" => 4700
			];

			$ten_metre_volume = $ten_metre_volume_array[$material];

			if($size == "5m") {
				$volume = $ten_metre_volume / 2;
			}

			if($size == "10m") {
				$volume = $ten_metre_volume;
			}

			if($size == "20m") {
				$volume = $ten_metre_volume * 2;
			}

			$weight = (20 * 30 / $cm_to_sqm_divisor) * $flag_weight_grams_per_sqm;
		
			//echo "<pre>" . print_r($volume, true) . '</pre>';
			
		} else {
			
			$sizes_array = array(
				$cart_item_data['_gravity_form_lead']['3'],
				$cart_item_data['_gravity_form_lead']['4'],
				$cart_item_data['_gravity_form_lead']['5'],
				$cart_item_data['_gravity_form_lead']['6']
			);

			foreach ( $sizes_array as $size_val ) {
				if ( $size_val ) {
					$size = substr($size_val, 0, strpos($size_val, "|"));
					break;
				}
			}
			
		}
	
    } 
	
	// Get the category IDs associated with the product
	$category_ids = $product->get_category_ids();

	// Loop through the category IDs and get the corresponding category objects
	$categories = array();
	foreach ( $category_ids as $category_id ) {
		$category = get_term_by( 'id', $category_id, 'product_cat' );
		$categories[] = $category;
	}
	
	$is_coffin_drape = false;
	
	foreach ( $categories as $cat ) {
		if($cat->slug == 'national-coffin-drape-flags') {
			$is_coffin_drape = true;
		}
	}
	
	if($is_coffin_drape) {
		
		$volume = 229 * 114 * 0.21;
		$weight = 0.44;
		
	} else {
	
		// If 'Size' is set, try to get the dimensions from the 'national_flag_dimensions' database
		if ( ! empty( $size ) ) {
			
			
			$material = substr($cart_item_data['_gravity_form_lead']['1'], 0, strpos($cart_item_data['_gravity_form_lead']['1'], "|"));
// 			echo '<pre>' . print_r($material, true) . '</pre>';
			
			global $wpdb;
			
			//We need to add a material column to the DB for sewn and printed options
			//Then update the query to fetch this info

			$query = $wpdb->prepare("
				SELECT * FROM national_flag_dimensions WHERE Size = %s AND Material = %s",
				$size, $material);
			$dimensions = $wpdb->get_row($query);
			
			if(current_user_can('administrator')){
// 				echo "<pre>Query: " . print_r($query, true) . "</pre>";
// 				echo "<pre>Dimensions: " . print_r($dimensions, true) . "</pre>";
			}



			// If dimensions are found, calculate the volume
			if ( ! is_null( $dimensions ) ) {
				$product = wc_get_product( $product_id ); // get the product object again
				$product->set_width( $dimensions->Width ); // set the product dimensions
				$product->set_height( $dimensions->Height );
				$product->set_length( $dimensions->Length );
				$product->set_weight( $dimensions->Weight );
				$volume = $dimensions->Width * $dimensions->Length * $dimensions->Height;
				$weight = $dimensions->Weight;
			}
		}
	
	}

    $cart_item_data['volume'] = $volume;
	$cart_item_data['weight'] = $weight;
    return $cart_item_data;
}

?>
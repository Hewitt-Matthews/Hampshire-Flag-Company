<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';
// Include and configure phpseclib
use phpseclib3\Net\SFTP;

function create_xml_and_save_locally($order_id){
    $order = wc_get_order( $order_id );	
	
	//Back out if no order found
    if(!$order){
		// Handle the error, e.g., log it or display a message
		error_log("Failed to get order with ID: " . $order_id);
		return;
	}

	$date = new DateTime();
	$date->setTimestamp(strtotime($order->get_date_created()));

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<Clarity xmlns="http://www.touchsystems.co.uk/schemas" Type="Order" Source="Customer Website" DateTime="' . $date->format(DATE_ATOM) . '">';
    $xml .= '<Contact>';
    $xml .= '<Name>' . htmlspecialchars($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()) . '</Name>';
    $xml .= '<Company>' . htmlspecialchars($order->get_billing_company()) . '</Company>';
    $xml .= '<Telephone>' . htmlspecialchars($order->get_billing_phone()) . '</Telephone>';
    $xml .= '<Email>' . htmlspecialchars($order->get_billing_email()) . '</Email>';
    $xml .= '<Address>';
    $xml .= '<Address1>' . htmlspecialchars($order->get_billing_address_1()) . '</Address1>';
    $xml .= '<Address2>' . htmlspecialchars($order->get_billing_address_2()) . '</Address2>';
    $xml .= '<City>' . htmlspecialchars($order->get_billing_city()) . '</City>';
    $xml .= '<County>' . htmlspecialchars($order->get_billing_state()) . '</County>';
    $xml .= '<Postcode>' . htmlspecialchars($order->get_billing_postcode()) . '</Postcode>';
    $xml .= '<Country>' . htmlspecialchars($order->get_billing_country()) . '</Country>';
    $xml .= '</Address>';
    $xml .= '<Detail/>';
    $xml .= '<Comment/>';
    $xml .= '<Document>';
    $xml .= '<Title>Online Order REF ' . $order_id . '</Title>';
    $xml .= '<Notes/>';
    $xml .= '<Detail>';
	$xml .= '<JobType>Unknown</JobType>';
	$xml .= '<LeadSource>HFC Website Order</LeadSource>';
	$xml .= '<CustOrderNo>REF ' . $order_id . '</CustOrderNo>';
	$xml .= '</Detail>';

    foreach ($order->get_items() as $item_id => $item) {

        $partcode = $order->get_item_meta($item_id, 'Partcode', true);
		
		//Check if item is a stock flag, if so we need to handle partcode sligltly differently
		$terms_to_check = [
			'standard-national-flags',
			'pride-flags'
		];
		$is_product_stock_flag = is_product_in_category_or_parent($item->get_product_id(), $terms_to_check);
		
		//Coloured and custom bunting ID's as we need to update <description> tag and <partcode> tag
		$bunting_product_ids = [541656, 524934];
        
        //Check if its a variable product
        $variation_id = $item->get_variation_id();
        
        //Check if it's a gform product
        $item_meta = $order->get_item_meta($item_id);
        $gravity_forms_history = maybe_unserialize($item_meta['_gravity_forms_history'][0]);

        if ($variation_id) { // This means that the product is variable
            $variation = wc_get_product($variation_id);
            $partcode = get_post_meta($variation_id, "_part_code1", true);
            $partcode2 = get_post_meta($variation_id, "_part_code2", true);
            $partcode3 = get_post_meta($variation_id, "_part_code3", true);
			
			// Fetch Mandatory fields
			$mandatory_addon1_name = get_post_meta($variation_id, "_mandatory_addon1_name", true);
			$mandatory_addon1_part_code = get_post_meta($variation_id, "_mandatory_addon1_part_code", true);
			$mandatory_addon2_name = get_post_meta($variation_id, "_mandatory_addon2_name", true);
			$mandatory_addon2_part_code = get_post_meta($variation_id, "_mandatory_addon2_part_code", true);
			$mandatory_addon3_name = get_post_meta($variation_id, "_mandatory_addon3_name", true);
			$mandatory_addon3_part_code = get_post_meta($variation_id, "_mandatory_addon3_part_code", true);
        }

		
        $xml .= '<Item>';

		//Set Item description (name)
		if($variation_id) {
			// get all attributes and values in a string format
			$attributes_string = array();
			foreach ($variation->get_attributes() as $attribute_name => $attribute_value) {
				$taxonomy = wc_attribute_taxonomy_name_by_id($attribute_name);
				if (taxonomy_exists($taxonomy)) {
					// If this is a taxonomy attribute, then we need to handle it differently
					$term = get_term_by('slug', $attribute_value, $taxonomy);
					if (!is_wp_error($term) && is_object($term)) {
						// If we got a valid term, append it to the string
						$attributes_string[] = wc_attribute_label($taxonomy) . ': ' . $term->name;
					}
				} else {
					// If this is a custom product attribute, it's simpler
					$attributes_string[] = wc_attribute_label($attribute_name) . ': ' . $attribute_value;
				}
			}

			$attributes_string = implode(', ', $attributes_string); // joining all attribute strings

			$xml .= '<Description>' . htmlspecialchars($item->get_name() . ' (' . $attributes_string . ')') . '</Description>';
		} else if(in_array($item->get_product_id(), $bunting_product_ids)) { //Add in else statement for coloured and custom bunting
			
			$product_atts_array = [];
			$entry_id = $gravity_forms_history['_gravity_form_linked_entry_id'];
			$entry = GFAPI::get_entry($entry_id);

			foreach ($entry as $key => $value) {
				if (!is_numeric($key)) {
					continue; // ignore meta keys
				}
				$field = GFAPI::get_field($entry['form_id'], $key);
				$label = $field->label;
				$field_value_parts = explode('|', $value);
                $field_value_normalized = $field_value_parts[0];
				if($field_value_normalized) {
				
					$product_atts_array[htmlspecialchars($label)] = htmlspecialchars($field_value_normalized);
						
				}
			}

			$attributes_string = '';
			
			//echo '<pre>' . print_r($product_atts_array, true) . '</pre>';

			foreach ($product_atts_array as $key => $value) {
				$attributes_string .= $key . ': ' . $value . ', ';
			}

			$attributes_string = rtrim($attributes_string, ', '); // Remove the trailing comma and space

			$xml .= '<Description>' . htmlspecialchars($item->get_name() . ' (' . $attributes_string . ')') . '</Description>';
			
		} else if($gravity_forms_history) {
			
			$product_atts_array = [];
			$entry_id = $gravity_forms_history['_gravity_form_linked_entry_id'];
			$entry = GFAPI::get_entry($entry_id);

			foreach ($entry as $key => $value) {
				if (!is_numeric($key)) {
					continue; // ignore meta keys
				}
				$field = GFAPI::get_field($entry['form_id'], $key);
				$label = $field->label;
				$field_value_parts = explode('|', $value);
                $field_value_normalized = $field_value_parts[0];
				if($field_value_normalized) {
				
					$product_atts_array[htmlspecialchars($label)] = htmlspecialchars($field_value_normalized);
						
				}
			}

			$attributes_string = '';

			foreach ($product_atts_array as $key => $value) {
				
				if($key == "45 Degree Angle Cost") continue;
				
				if($value == "45 Degree Angle") {
					$attributes_string .= '45 Degree Add On, ';
				} else {
					$attributes_string .= $key . ': ' . $value . ', ';
				}
				
			}

			$attributes_string = rtrim($attributes_string, ', '); // Remove the trailing comma and space

			$xml .= '<Description>' . htmlspecialchars($item->get_name() . ' (' . $attributes_string . ')') . '</Description>';
			
		} else {
			$xml .= '<Description>' . htmlspecialchars($item->get_name()) . '</Description>';
		}

        $xml .= '<Quantity>' . htmlspecialchars($item->get_quantity()) . '</Quantity>';
        $xml .= '<UnitPrice>' . htmlspecialchars($item->get_subtotal() / $item->get_quantity()) . '</UnitPrice>';
	
		//Handle Custom Product Partcodes
		$form_id = $gravity_forms_history['_gravity_form_lead']['form_id'];
		$custom_product_form_ids = [21, 22, 23, 24, 25, 26, 27, 31, 37];
	
		//Check if custom product form is used, but not bunting
		if(in_array($form_id, $custom_product_form_ids) && !in_array($item->get_product_id(), $bunting_product_ids)) {
			
			$custom_product_part_code_letters =	[
				"Material" => [
					"Knitted Polyester" => "KP",
					"Recycled Knitted Polyester" => "RKP",
				],
				"Number of Sides" => [
					"Single Sided" => "SS",
					"Double Sided" => "DS",
				],
				"Finishing" => [
					"Rope and Toggle" => "RT",
					"D Rings" => "DR",
					"Eyelets" => "EY",
					"Inglefield Clips" => "IC",
					"Sleeve & Eyelets" => "SLEY",
					"Sleeve" => "SL"
				]
			];
			
			$product_atts_array = [];
			$entry_id = $gravity_forms_history['_gravity_form_linked_entry_id'];
			$entry = GFAPI::get_entry($entry_id);

			foreach ($entry as $key => $value) {
				if (!is_numeric($key)) {
					continue; // ignore meta keys
				}
				$field = GFAPI::get_field($entry['form_id'], $key);
				$label = $field->label;
				$field_value_parts = explode('|', $value);
                $field_value_normalized = $field_value_parts[0];
				if($field_value_normalized) {
				
					$product_atts_array[htmlspecialchars($label)] = htmlspecialchars($field_value_normalized);
						
				}
			}
			
			$selection_partcode_acronym_array = [];
			
			foreach($custom_product_part_code_letters as $selection_type => $selection_array) {
				
				$selection = $product_atts_array[$selection_type];
				
				if($product_atts_array[$selection_type]) {
					
					array_push($selection_partcode_acronym_array, $selection_array[$selection]);
						
				}
				
			}
			
			//echo '<pre>Selected Attributes:' . print_r($product_atts_array, true) .'</pre>';
			$custom_product_partcode = implode('-', $selection_partcode_acronym_array);
			$custom_length = $product_atts_array["Height (cm)"] * 100;
			$custom_width = $product_atts_array["Width (cm)"] * 100;
			
			$xml .= '<Partcode>' . $custom_product_partcode . '</Partcode>';
			$xml .= '<Length>' . $custom_length . '</Length>';
			$xml .= '<Width>' . $custom_width . '</Width>';
			
		}
		
		//Check id product is custom or coloured bunting, if so update partcode.
		
		if(in_array($item->get_product_id(), $bunting_product_ids)) {
			
			$bunting_part_code_letters = [
				"Type" => [
					'524934' => 'CUS',
					'541656' => 'COL'
				],
				"Material" => [
					"Knitted Polyester" => "KP",
					"Recycled Knitted Polyester" => "RKP",
					"Paper" => "PA",
					"Heavy Duty PVC" => "HDPVC",
					"PVC" => "PVC",
					"PVC Free" => "NOPVC"
				],
				"Length Size" => [
					"5m" => "05",
					"10m" => "10",
					"20m" => "20",
					"5m (12 pennants)" => "05",
					"10m (24 pennants)" => "10",
					"20m (48 pennants)" => "20"
				],
				"Shape" => [
					"Triangular" => "TRI",
					"Rectangular" => "REC"
				]
			];
				
			$product_atts_array = [];
			$entry_id = $gravity_forms_history['_gravity_form_linked_entry_id'];
			$entry = GFAPI::get_entry($entry_id);

			foreach ($entry as $key => $value) {
				if (!is_numeric($key)) {
					continue; // ignore meta keys
				}
				$field = GFAPI::get_field($entry['form_id'], $key);
				$label = $field->label;
				$field_value_parts = explode('|', $value);
                $field_value_normalized = $field_value_parts[0];
				if($field_value_normalized) {
				
					$product_atts_array[htmlspecialchars($label)] = htmlspecialchars($field_value_normalized);
						
				}
			}
			
			$selection_partcode_acronym_array = [];
			
			foreach($bunting_part_code_letters as $selection_type => $selection_array) {
				
				if($selection_type == "Type") {
					$selection = $selection_array[$item->get_product_id()];
					array_push($selection_partcode_acronym_array, $selection);
				} else {
					$selection = $product_atts_array[$selection_type];
				}
				
				if($product_atts_array[$selection_type]) {
					
					array_push($selection_partcode_acronym_array, $selection_array[$selection]);
						
				}
				
			}
			
			//echo '<pre>Selected Attributes:' . print_r($product_atts_array, true) .'</pre>';
			$custom_product_partcode = implode('-', $selection_partcode_acronym_array);
			//echo '<pre>PartCode:' . print_r($custom_product_partcode, true) .'</pre>';
			
			$partcode = $custom_product_partcode;
			
		}
		
		if(!$is_product_stock_flag && $partcode3) {
			$xml .= '<SubItem>';
				$xml .= '<Description>' . htmlspecialchars($item->get_name())  . '</Description>';
				$xml .= '<Partcode>' . $partcode . '</Partcode>';
			$xml .= '</SubItem>';
			$xml .= '<SubItem>';
				$xml .= '<Description>' . htmlspecialchars($item->get_name())  . '</Description>';
				$xml .= '<Partcode>' . $partcode2 . '</Partcode>';
			$xml .= '</SubItem>';
			$xml .= '<SubItem>';
				$xml .= '<Description>' . htmlspecialchars($item->get_name())  . '</Description>';
				$xml .= '<Partcode>' . $partcode3 . '</Partcode>';
			$xml .= '</SubItem>';
			
		} else if(!$is_product_stock_flag && $partcode2) {
			
			$xml .= '<SubItem>';
				$xml .= '<Description>' . htmlspecialchars($item->get_name())  . '</Description>';
				$xml .= '<Partcode>' . $partcode . '</Partcode>';
			$xml .= '</SubItem>';
			$xml .= '<SubItem>';
				$xml .= '<Description>' . htmlspecialchars($item->get_name())  . '</Description>';
				$xml .= '<Partcode>' . $partcode2 . '</Partcode>';
			$xml .= '</SubItem>';
			
		} else if(!$is_product_stock_flag && $partcode) {
			$xml .= '<Partcode>' . $partcode . '</Partcode>';
		}
		
	
		// Check for Mandatory fields
		if($mandatory_addon1_name && $mandatory_addon1_part_code) {
			$xml .= '<SubItem>';
				$xml .= '<Description>' . htmlspecialchars($mandatory_addon1_name)  . '</Description>';
				$xml .= '<Partcode>' . $mandatory_addon1_part_code . '</Partcode>';
				$xml .= '<Quantity>1</Quantity>';
			$xml .= '</SubItem>';
		}
		if($mandatory_addon2_name && $mandatory_addon2_part_code) {
			$xml .= '<SubItem>';
				$xml .= '<Description>' . htmlspecialchars($mandatory_addon2_name)  . '</Description>';
				$xml .= '<Partcode>' . $mandatory_addon2_part_code . '</Partcode>';
				$xml .= '<Quantity>1</Quantity>';
			$xml .= '</SubItem>';
		}
		if($mandatory_addon3_name && $mandatory_addon3_part_code) {
			$xml .= '<SubItem>';
				$xml .= '<Description>' . htmlspecialchars($mandatory_addon3_name)  . '</Description>';
				$xml .= '<Partcode>' . $mandatory_addon3_part_code . '</Partcode>';
				$xml .= '<Quantity>1</Quantity>';
			$xml .= '</SubItem>';
		}
		
		
		//If it's a simple product, fetch the SKU for the Partcode
		
		if(!$variation_id && !$gravity_forms_history) {
			$simple_product = $item->get_product();
			
			// Check if the product is valid
			if ($simple_product) {
				$sku = $simple_product->get_sku();
				$xml .= '<Partcode>' . htmlspecialchars($sku) . '</Partcode>';
			}
		
		}
	
		//If it a gform product but NOT a custom product, fetch the selected options, check if they have their own part codes
		//And if so, add that as a <subitem> to the <item> tag
		
		if($gravity_forms_history && !in_array($form_id, $custom_product_form_ids) ) {
			
			$attribute_part_codes =	[
				"Add anti-fray netting?" => [
					"1/2 Yard (45cm x 22cm)" => "Antifray1",
					"3/4 Yard (68cm x 34cm)" => "Antifray1",
					"1 Yard (91cm x 45cm)" => "Antifray1",
					"3' x 2' (91cm x 61cm)" => "Antifray1",
					"1 & 1/4 Yard (114cm x 56cm)" => "Antifray1",
					"1 & 1/2 Yard (137cm x 68cm)" => "Antifray1",
					"5' x 3' (152cm x 91cm)" => "Antifray1",
					"2 Yard (183cm x 91cm)" => "Antifray1",
					"6' x 4' (183cm x 122cm)" => "Antifray1",
					"2 & 1/2 Yard (229cm x 114cm)" => "Antifray2",
					"3 Yard (274cm x 137cm)" => "Antifray2",
					"4 Yard (365cm x 183cm)" => "Antifray2",
					"5 Yard (458cm x 228cm)" => "Antifray3",
					"6 Yard (548cm x 274cm)" => "Antifray3",
				],
				"Inglefield Clips" => "Inglefield Clip",
				"Nickel Eyelets" => "Eyelets",
				"Plastic D-Rings" => "D-Rings",
				"45 Degree Angle Cost" => 20007,
				"18mm Pole Sleeve" => 20008,
				"32mm Pole Sleeve" => 20008,
				"Add Gold Fringe" => "CEREMONIAL-GOLDFRINGE"
			];
			
			$product_atts_array = [];
			$entry_id = $gravity_forms_history['_gravity_form_linked_entry_id'];
			$entry = GFAPI::get_entry($entry_id);

			foreach ($entry as $key => $value) {
				if (!is_numeric($key)) {
					continue; // ignore meta keys
				}
				$field = GFAPI::get_field($entry['form_id'], $key);
				$label = $field->label;
				$field_value_parts = explode('|', $value);
                $field_value_normalized = $field_value_parts[0];
				if($field_value_normalized) {
				
					$product_atts_array[htmlspecialchars($label)] = htmlspecialchars($field_value_normalized);
						
				}
			}
			
//             echo '<pre>Selected Attributes:' . print_r($product_atts_array, true) .'</pre>';
//             echo '<pre>Attributes with Part codes:' . print_r($attribute_part_codes, true) .'</pre>';

			if($is_product_stock_flag && !in_array($item->get_product_id(), $bunting_product_ids)) {
				
				if($product_atts_array['Add anti-fray netting?'] === "No" && $product_atts_array['Finishing'] === "Rope and Toggle"  && $product_atts_array['Shape'] === "Rectangular") {
					
					if($partcode) {
						$xml .= '<Partcode>' . $partcode . '</Partcode>';
					}
					$xml .= '<Description>' . htmlspecialchars($item->get_name())  . '</Description>';
					$xml .= '<Quantity>' . htmlspecialchars($item->get_quantity()) . '</Quantity>';

				} elseif($product_atts_array['Material'] === "Novelty") {
					
					if($partcode) {
						$xml .= '<Partcode>' . $partcode . '</Partcode>';
					}
					
				} else {
					$xml .= '<Partcode>PWITEM</Partcode>';
					$xml .= '<SubItem>';
						if($partcode) {
							$xml .= '<Partcode>' . $partcode . '</Partcode>';
						}
						$xml .= '<Description>' . htmlspecialchars($item->get_name())  . '</Description>';
						//$xml .= '<Quantity>' . htmlspecialchars($item->get_quantity()) . '</Quantity>';
						//Updated Below as per Robert Request 03/11/2023
						$xml .= '<Quantity>1</Quantity>';
					$xml .= '</SubItem>';
				}
				
				
			}
			
			
			// Loop through selected options and add subitem to xml if needed, exclude bunting and Novelty
			if(!in_array($item->get_product_id(), $bunting_product_ids) && ($product_atts_array['Material'] !== "Novelty")) {

				foreach($product_atts_array as $attribute_key => $value) {

					if($attribute_key == 'Add anti-fray netting?') {
						if($value == "Yes") {
							$xml .= '<SubItem>';
							$xml .= '<Partcode>' . $attribute_part_codes['Add anti-fray netting?'][$product_atts_array['Size']] . '</Partcode>';
							$xml .= '<Description>Anti-fray netting</Description>';
							//$xml .= '<Quantity>' . htmlspecialchars($item->get_quantity()) . '</Quantity>';
							//Updated Below as per Robert Request 03/11/2023
							$xml .= '<Quantity>1</Quantity>';
							$xml .= '</SubItem>';
						}

					} else if($attribute_key == 'Finishing') {
						if($value != "Rope and Toggle") {
							$xml .= '<SubItem>';
							$xml .= '<Partcode>' . $attribute_part_codes[$value] . '</Partcode>';
							$xml .= '<Description>' . $value . '</Description>';
							//$xml .= '<Quantity>' . htmlspecialchars($item->get_quantity()) . '</Quantity>';
							//Updated Below as per Robert Request 03/11/2023
							$xml .= '<Quantity>1</Quantity>';
							$xml .= '</SubItem>';
						}

					} else if ($attribute_part_codes[$attribute_key]) {
						$xml .= '<SubItem>';
						$xml .= '<Partcode>' . $attribute_part_codes[$attribute_key] . '</Partcode>';
						$xml .= '<Description>' . $attribute_key . '</Description>';
						//$xml .= '<Quantity>' . htmlspecialchars($item->get_quantity()) . '</Quantity>';
						//Updated Below as per Robert Request 03/11/2023
						$xml .= '<Quantity>1</Quantity>';
						$xml .= '</SubItem>';
					} 
// 					else if($value && $attribute_key) {

// 						$dissallowed_atts = ["Material", "Size", "Shape"];
						
// 						if($value != 1 || !in_array($attribute_key, $dissallowed_atts)) {
// 							$xml .= '<SubItem>';
// 							$xml .= '<Partcode>' . $attribute_key . '</Partcode>';
// 							$xml .= '<Description>' . $value . '</Description>';
// 							$xml .= '<Quantity>1</Quantity>';
// 							$xml .= '</SubItem>';
// 						}

// 					}


				}
				
			}
			
		}
	
        $xml .= '</Item>';
    }
	
	if($order->get_shipping_total()) {
		
		$xml .= '<Item>';
		$xml .= '<Partcode>DELIVERY</Partcode>';
		$xml .= '<Description>DELIVERY</Description>';
		$xml .= '<Quantity>1</Quantity>';
		$xml .= '<UnitPrice>' . htmlspecialchars($order->get_shipping_total()) . '</UnitPrice>';
		$xml .= '</Item>';
		
	}
	
    $xml .= '<Totals/>';
    $xml .= '<DeliveryAddress>';
	if($order->get_shipping_first_name() || $order->get_shipping_last_name()) {
		$xml .= '<Contact>' . htmlspecialchars($order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name()) . '</Contact>';
	} else {
		$xml .= '<Contact>' . htmlspecialchars($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()) . '</Contact>';
	}
    $xml .= '<Address1>' . htmlspecialchars($order->get_shipping_address_1()) . '</Address1>';
    $xml .= '<Address2>' . htmlspecialchars($order->get_shipping_address_2()) . '</Address2>';
    $xml .= '<City>' . htmlspecialchars($order->get_shipping_city()) . '</City>';
    $xml .= '<County>' . htmlspecialchars($order->get_shipping_state()) . '</County>';
    $xml .= '<Postcode>' . htmlspecialchars($order->get_shipping_postcode()) . '</Postcode>';
    $xml .= '<Telephone>' . htmlspecialchars($order->get_billing_phone()) . '</Telephone>';
    $xml .= '</DeliveryAddress>';
    $xml .= '</Document>';
    $xml .= '</Contact>';
    $xml .= '</Clarity>';

    $upload_dir = wp_upload_dir();
    $xml_folder = $upload_dir['basedir'] . '/orders/';

    if(!file_exists($xml_folder)) {
        wp_mkdir_p($xml_folder);
    }

    $file_name = 'order_'.$order_id.'.xml';
    $file_path = $xml_folder . $file_name ;
    file_put_contents($file_path, $xml);
	
	// Remote server connection settings
	$remote_host = 'app.hampshireflag.co.uk';
	$remote_port = 5055;
	$remote_username = SFTP_CLARITY_USER;
	$remote_password = SFTP_CLARITY_PASS;

	// Remote file path
	$remote_file_path = '/ClarityXML/' . $file_name;

	$sftp = new SFTP($remote_host, $remote_port);

	if (!$sftp->login($remote_username, $remote_password)) {
		echo "<!-- <pre>SFTP login failed</pre> -->";
		exit;
	}

	if ($sftp->put($remote_file_path, $file_path, SFTP::SOURCE_LOCAL_FILE)) {
		echo "<!-- <pre>File uploaded successfully</pre> -->";
	} else {
		echo "<!-- <pre>File upload failed</pre> -->";
	}

	
}
//add_action( 'woocommerce_payment_complete', 'create_xml_and_save_locally' );
add_action( 'woocommerce_thankyou', 'create_xml_and_save_locally', 20, 10 );
?>
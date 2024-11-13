<?php

// ADD CUSTOM VARIATION FIELDS

// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );

// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

/**
 * Create new fields for variations
 *
*/
function variation_settings_fields( $loop, $variation_data, $variation ) {

	// Text Field - Part Code 1
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_part_code1[' . $variation->ID . ']', 
			'label'       => __( 'Part Code 1', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Part Code.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_part_code1', true )
		)
	);
	
	// Text Field - Part Code 2
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_part_code2[' . $variation->ID . ']', 
			'label'       => __( 'Part Code 2', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Part Code.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_part_code2', true )
		)
	);
	
	// Text Field - Part Code 3
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_part_code3[' . $variation->ID . ']', 
			'label'       => __( 'Part Code 3', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Part Code.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_part_code3', true )
		)
	);
	

	// Text Field - MANDATORY Add-on 1 Name
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_mandatory_addon1_name[' . $variation->ID . ']', 
			'label'       => __( 'Mandatory Add-on 1 - Name', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Name.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_mandatory_addon1_name', true )
		)
	);	
	
	// Text Field - MANDATORY Add-on 1 Part Code
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_mandatory_addon1_part_code[' . $variation->ID . ']', 
			'label'       => __( 'Mandatory Add-on 1 - Part Code', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Part Code.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_mandatory_addon1_part_code', true )
		)
	);	
	
	
	// Text Field - MANDATORY Add-on 2 Name
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_mandatory_addon2_name[' . $variation->ID . ']', 
			'label'       => __( 'Mandatory Add-on 2 - Name', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Name.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_mandatory_addon2_name', true )
		)
	);	
	
	// Text Field - MANDATORY Add-on 2 Part Code
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_mandatory_addon2_part_code[' . $variation->ID . ']', 
			'label'       => __( 'Mandatory Add-on 2 - Part Code', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Part Code.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_mandatory_addon2_part_code', true )
		)
	);	
	
	// Text Field - MANDATORY Add-on 3 Name
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_mandatory_addon3_name[' . $variation->ID . ']', 
			'label'       => __( 'Mandatory Add-on 3 - Name', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Name.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_mandatory_addon3_name', true )
		)
	);	
	
	// Text Field - MANDATORY Add-on 3 Part Code
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_mandatory_addon3_part_code[' . $variation->ID . ']', 
			'label'       => __( 'Mandatory Add-on 3 - Part Code', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter Part Code.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_mandatory_addon3_part_code', true )
		)
	);	


}

/**
 * Save new fields for variations
 *
*/
function save_variation_settings_fields( $post_id ) {

	// Text Field 1
	$text_field1 = $_POST['_part_code1'][ $post_id ];
	if( ! empty( $text_field1 ) ) {
		update_post_meta( $post_id, '_part_code1', esc_attr( $text_field1 ) );
	}
	
	// Text Field 2
	$text_field2 = $_POST['_part_code2'][ $post_id ];
	if( ! empty( $text_field2 ) ) {
		update_post_meta( $post_id, '_part_code2', esc_attr( $text_field2 ) );
	}
	
	// Text Field 3
	$text_field3 = $_POST['_part_code3'][ $post_id ];
	if( ! empty( $text_field3 ) ) {
		update_post_meta( $post_id, '_part_code3', esc_attr( $text_field3 ) );
	}
	
	
	// Mandatory Add-on 1 Name Field
	$mandatory_addon1_name = $_POST['_mandatory_addon1_name'][ $post_id ];
	if( ! empty( $mandatory_addon1_name ) ) {
		update_post_meta( $post_id, '_mandatory_addon1_name', esc_attr( $mandatory_addon1_name ) );
	}
	
	// Mandatory Add-on 1 Partcode Field
	$mandatory_addon1_part_code = $_POST['_mandatory_addon1_part_code'][ $post_id ];
	if( ! empty( $mandatory_addon1_part_code ) ) {
		update_post_meta( $post_id, '_mandatory_addon1_part_code', esc_attr( $mandatory_addon1_part_code ) );
	}
	
	// Mandatory Add-on 2 Name Field
	$mandatory_addon2_name = $_POST['_mandatory_addon2_name'][ $post_id ];
	if( ! empty( $mandatory_addon2_name ) ) {
		update_post_meta( $post_id, '_mandatory_addon2_name', esc_attr( $mandatory_addon2_name ) );
	}
	
	// Mandatory Add-on 2 Partcode Field
	$mandatory_addon2_part_code = $_POST['_mandatory_addon2_part_code'][ $post_id ];
	if( ! empty( $mandatory_addon2_part_code ) ) {
		update_post_meta( $post_id, '_mandatory_addon2_part_code', esc_attr( $mandatory_addon2_part_code ) );
	}
	
	// Mandatory Add-on 3 Name Field
	$mandatory_addon3_name = $_POST['_mandatory_addon3_name'][ $post_id ];
	if( ! empty( $mandatory_addon3_name ) ) {
		update_post_meta( $post_id, '_mandatory_addon3_name', esc_attr( $mandatory_addon3_name ) );
	}
	
	// Mandatory Add-on 3 Partcode Field
	$mandatory_addon3_part_code = $_POST['_mandatory_addon3_part_code'][ $post_id ];
	if( ! empty( $mandatory_addon3_part_code ) ) {
		update_post_meta( $post_id, '_mandatory_addon3_part_code', esc_attr( $mandatory_addon3_part_code ) );
	}


}
?>
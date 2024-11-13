<?php

function add_custom_fields_simple() {
    echo '<div class="options_group">';
    woocommerce_wp_text_input(
        array(
            'id'          => '_custom_length',
            'label'       => __( 'Custom Length', 'woocommerce' ),
            'placeholder' => 'cm',
            'desc_tip'    => 'true',
        )
    );
	woocommerce_wp_text_input(
        array(
            'id'          => '_custom_width',
            'label'       => __( 'Custom Width', 'woocommerce' ),
            'placeholder' => 'cm',
            'desc_tip'    => 'true',
        )
    );
	woocommerce_wp_text_input(
        array(
            'id'          => '_custom_depth',
            'label'       => __( 'Custom Depth', 'woocommerce' ),
            'placeholder' => 'cm',
            'desc_tip'    => 'true',
        )
    );
    // Repeat above for other dimensions width, height
    echo '</div>';
}
add_action( 'woocommerce_product_options_shipping', 'add_custom_fields_simple' );

function add_custom_fields_variable( $loop, $variation_data, $variation ) {
    woocommerce_wp_text_input(
        array(
            'id'          => '_custom_length[' . $variation->ID . ']',
            'label'       => __( 'Custom Length', 'woocommerce' ),
			'placeholder' => 'cm',
            'desc_tip'    => 'true',
            'value'       => get_post_meta( $variation->ID, '_custom_length', true )
        )
    );
	woocommerce_wp_text_input(
        array(
            'id'          => '_custom_width[' . $variation->ID . ']',
            'label'       => __( 'Custom Width', 'woocommerce' ),
			'placeholder' => 'cm',
            'desc_tip'    => 'true',
            'value'       => get_post_meta( $variation->ID, '_custom_width', true )
        )
    );
	woocommerce_wp_text_input(
        array(
            'id'          => '_custom_depth[' . $variation->ID . ']',
            'label'       => __( 'Custom Depth', 'woocommerce' ),
			'placeholder' => 'cm',
            'desc_tip'    => 'true',
            'value'       => get_post_meta( $variation->ID, '_custom_depth', true )
        )
    );
    // Repeat above for other dimensions width, height
}
add_action( 'woocommerce_product_after_variable_attributes', 'add_custom_fields_variable', 10, 3 );

function save_custom_fields_simple( $post_id ) {
    $custom_length = $_POST['_custom_length'];
    if ( ! empty( $custom_length ) ) {
        update_post_meta( $post_id, '_custom_length', esc_attr( $custom_length ) );
    } else {
        delete_post_meta( $post_id, '_custom_length' );
    }
	
	$custom_width = $_POST['_custom_width'];
    if ( ! empty( $custom_width ) ) {
        update_post_meta( $post_id, '_custom_width', esc_attr( $custom_width ) );
    } else {
        delete_post_meta( $post_id, '_custom_width' );
    } 
	
	$custom_depth = $_POST['_custom_depth'];
    if ( ! empty( $custom_depth ) ) {
        update_post_meta( $post_id, '_custom_depth', esc_attr( $custom_depth ) );
    } else {
        delete_post_meta( $post_id, '_custom_depth' );
    } 
    // Repeat above for other dimensions width, height
}
add_action( 'woocommerce_process_product_meta', 'save_custom_fields_simple' );

function save_custom_fields_variable( $variation_id, $i ) {
    $custom_length = $_POST['_custom_length'][ $variation_id ];
    if ( isset( $custom_length ) ) {
        update_post_meta( $variation_id, '_custom_length', esc_attr( $custom_length ) );
    }
	
	$custom_width = $_POST['_custom_width'][ $variation_id ];
    if ( isset( $custom_width ) ) {
        update_post_meta( $variation_id, '_custom_width', esc_attr( $custom_width ) );
    }
	
	$custom_depth = $_POST['_custom_depth'][ $variation_id ];
    if ( isset( $custom_depth ) ) {
        update_post_meta( $variation_id, '_custom_depth', esc_attr( $custom_depth ) );
    }
    // Repeat above for other dimensions width, height
}
add_action( 'woocommerce_save_product_variation', 'save_custom_fields_variable', 10, 2 );


function use_custom_dimensions($package) {
    foreach ( $package['contents'] as $item_id => $values ) {
        $product = $values['data'];
        $custom_length = get_post_meta( $product->get_id(), '_custom_length', true );
        if ( ! empty( $custom_length ) ) {
            $product->set_length($custom_length);
        }
		
		$custom_width = get_post_meta( $product->get_id(), '_custom_width', true );
        if ( ! empty( $custom_width ) ) {
            $product->set_length($custom_width);
        }
		
		$custom_depth = get_post_meta( $product->get_id(), '_custom_depth', true );
        if ( ! empty( $custom_depth ) ) {
            $product->set_length($custom_depth);
        }
        // Repeat above for other dimensions width, height
    }
    return $package;
}
//add_filter( 'woocommerce_cart_shipping_packages', 'use_custom_dimensions' );


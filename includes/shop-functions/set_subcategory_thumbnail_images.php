<?php

function set_subcategory_thumbnail() {
    if ( is_product_category() ) {
        $category = get_queried_object(); // get the current category object
		
        $subcategories = get_terms( array(
			'taxonomy' => 'product_cat',
            'parent' => $category->term_id,
            'hide_empty' => false
        ) );

        foreach ( $subcategories as $subcategory ) {
            $thumbnail_id = get_term_meta( $subcategory->term_id, 'thumbnail_id', true );
			//echo '<pre> Meta: ' . print_r(get_term_meta($subcategory->term_id), true) . '</pre>';
            if ( empty( $thumbnail_id ) ) {
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 1,
                    'product_cat' => $subcategory->slug,
                    'fields' => 'ids',
                );
                $product_query = new WP_Query( $args );
                $product_id = $product_query->posts[0];

                if ( has_post_thumbnail( $product_id ) ) {
                    $thumbnail_id = get_post_thumbnail_id( $product_id );
                    update_term_meta( $subcategory->term_id, 'thumbnail_id', $thumbnail_id );
                }
            }
        }
    }
}
//add_action( 'woocommerce_before_shop_loop', 'set_subcategory_thumbnail' );

?>
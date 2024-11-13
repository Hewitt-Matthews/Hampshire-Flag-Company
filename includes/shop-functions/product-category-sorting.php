<?php

function custom_sort_products( $query ) {
	
	if ( $query->is_main_query() && is_product_category( ) ) {
		// Sort the remaining products in alphabetical order
		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
	}
		

}
add_action( 'pre_get_posts', 'custom_sort_products' );

<?php

function wpse_298888_posts_where( $where, $query ) {
    global $wpdb;

    $starts_with = esc_sql( $query->get( 'starts_with' ) );

    if ( $starts_with ) {
        $where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
    }

    return $where;
}
add_filter( 'posts_where', 'wpse_298888_posts_where', 10, 2 );

function wc_custom_product_filter() {
	
	// Replace these IDs with the ones you want to display the filter on
    $category_ids = array( 24, 550, 568, 970, 971, 972, 973, 974, 1274, 1275, 1276, 1277, 1278, 782, 783, 784, 785, 787, 768, 1567, 1565, 1566, 1569, 1568, 767, 1737);
    
    // Get the current category ID
    $category_id = get_queried_object_id();

    // Check if the current category ID is in the array
    if ( in_array( $category_id, $category_ids ) ) {
	
		$letters = range('A', 'Z');
		array_unshift($letters, 'Show All');
		$category_name = single_cat_title('', false);
		$cat_slug = get_queried_object()->slug;
		$filter_html = '<div class="wc-product-filter">';
		$filter_html .= '<p>Search ' . $category_name . ' beginning with:</p>';
		$filter_html .= '<ul>';
		foreach ($letters as $letter) {
			$filter_html .= '<li><a href="#" class="wc-custom-filter-link" data-filter="' . $letter . '" data-slug="' . $cat_slug . '">' . $letter . '</a></li>';
		}
		$filter_html .= '</ul>';
		$filter_html .= '</div>';
		echo $filter_html;
	
	}
}

add_action('woocommerce_before_shop_loop', 'wc_custom_product_filter');

add_action('wp_ajax_wc_custom_filter_products', 'wc_custom_filter_products');
add_action('wp_ajax_nopriv_wc_custom_filter_products', 'wc_custom_filter_products');

function wc_custom_filter_products() {
    $letter = $_POST['letter'];
	$category = $_POST['slug'];
	
	// Get the category IDs that contain the word "Non National" in the name
    $non_national_categories = get_terms( array(
        'taxonomy' => 'product_cat',
        'name__like' => 'Non National',
        'fields' => 'ids',
    ) );
	
// 	print_r($price_categories);
	
	if ($category === 'standard-national-flags') {
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $category
				),
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'operator' => 'NOT IN',
					'terms' => $non_national_categories,
				)
			),
			'orderby' => 'menu_order title',
			'order' => 'ASC',
			'starts_with' => $letter == "Show All" ? NULL :  $letter
		);
	} else {
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $category
				),
			),
			'orderby' => 'menu_order title',
			'order' => 'ASC',
			'starts_with' => $letter == "Show All" ? NULL :  $letter
		);
	}
	
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
	else: 
		?> <p>There are no products in this category that begin with the letter <?= $letter ?></p> <?php
    endif;
    wp_die();
}

function wc_custom_scripts() {
    wp_enqueue_script('wc-custom-jquery', get_stylesheet_directory_uri() . '/includes/js/product-category-filter.js', array('jquery'), THEME_VERSION, true);
    wp_localize_script('wc-custom-jquery', 'wc_ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'wc_custom_scripts');

?>
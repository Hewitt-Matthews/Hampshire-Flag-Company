<?php 

add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_get_catalog_ordering_args' );
function custom_get_catalog_ordering_args( $args ) {
    if ( isset( $_GET['orderby'] ) ) {
        // Sort by "menu_order" DESC (the default option)
        if ( 'title_desc' === $_GET['orderby'] ) {
            $args = array( 'orderby' => 'title', 'order' => 'DESC' );
        }
        // Sort by "menu_order" ASC
        elseif ( 'title_asc' == $_GET['orderby'] ) {
            $args = array( 'orderby'  => 'title', 'order' => 'ASC' );
        }
        // Make a clone of "menu_order" (the default option)
        elseif ( 'natural_order' == $_GET['orderby'] ) {
            $args = array( 'orderby'  => 'menu_order title', 'order' => 'ASC' );
        }
    }
    return $args;
}

add_filter( 'woocommerce_catalog_orderby', 'custom_catalog_orderby' );
function custom_catalog_orderby( $orderby ) {
    // Insert "Sort alphabetically (desc.)" and the clone of "menu_order" adding after others sorting options
    return $orderby + array(
        'title_desc'    => __('Sort alphabetically (desc.)', 'woocommerce'), // default
        'title_asc'     => __('Sort alphabetically (asc.)', 'woocommerce')
    ) ;

    return $orderby ;
}

//add_filter( 'woocommerce_default_catalog_orderby', 'custom_default_catalog_orderby' );
function custom_default_catalog_orderby( $default_orderby ) {
    return 'title_desc';
}

add_action( 'woocommerce_product_query', 'product_query_sort_alphabetically' );
function product_query_sort_alphabetically( $q ) {
    if ( ! isset( $_GET['orderby'] ) && ! is_admin() ) {
        $q->set( 'orderby', 'title' );
        $q->set( 'order', 'DESC' );
    }
}
<?php
// Override the plugin's function to hide hidden categories on single product pages
add_action( 'init', 'remove_hide_hidden_categories_single_product_filter', 20 );

function remove_hide_hidden_categories_single_product_filter() {
    global $Hide_Categories_Products_WC;

    if ( isset( $Hide_Categories_Products_WC ) && method_exists( $Hide_Categories_Products_WC, 'hide_hidden_categories_single_product' ) ) {
        remove_filter( 'get_the_terms', array( $Hide_Categories_Products_WC, 'hide_hidden_categories_single_product' ), 11 );
    }
}


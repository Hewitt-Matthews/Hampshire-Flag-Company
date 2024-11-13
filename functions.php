<?php

//Include all shortcode files
foreach (glob(__DIR__ . "/includes/shortcodes/*.php") as $filename) {
    include $filename;
}

//Include all shop function files
foreach (glob(__DIR__ . "/includes/shop-functions/*.php") as $filename) {
    include $filename;
}

$theme = wp_get_theme();
define('THEME_VERSION', $theme->Version);

require 'includes/hm-storefront-template-changes.php';
require 'includes/hm-woo-product-page.php';
require 'includes/hm-woo-cart-and-checkout.php';
require 'includes/hm-core-functions.php';

add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' );


function my_enqueue_assets() { 
	
	global $post;
	
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css', array(), THEME_VERSION ); 
	wp_enqueue_style( 'header-styles', get_stylesheet_directory_uri().'/includes/css/header.css', array(), THEME_VERSION ); 
	wp_enqueue_style( 'main-styles', get_stylesheet_directory_uri().'/includes/css/main.css', array(), THEME_VERSION ); 
	wp_enqueue_script( 'global_js', get_stylesheet_directory_uri() . '/includes/js/all-pages.js', array(), THEME_VERSION);
	wp_enqueue_script( 'chat_js', get_stylesheet_directory_uri() . '/includes/js/chat.js', array(), THEME_VERSION);
	
// 	wp_enqueue_style( 'aos-style', 'https://unpkg.com/aos@2.3.1/dist/aos.css');
// 	wp_enqueue_script( 'aos_js', 'https://unpkg.com/aos@2.3.1/dist/aos.js');

	if( is_front_page() ) {
		//wp_enqueue_style( 'homepage-styles', get_stylesheet_directory_uri().'/includes/css/homepage-styles.css', array(), THEME_VERSION ); 
	} elseif( is_home( ) ) {
		//wp_enqueue_style( 'blog_parent_page-styles', get_stylesheet_directory_uri().'/css/blog-parent.css', array(), THEME_VERSION  ); 
		wp_enqueue_script( 'posts-filter-script', get_stylesheet_directory_uri().'/includes/js/posts-filter.js' ); 
	} elseif( is_singular( 'product' ) ) {
		wp_enqueue_style( 'product_single_page-styles', get_stylesheet_directory_uri().'/includes/css/woo-products-page-single.css', array(), THEME_VERSION ); 
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		wp_enqueue_script( 'product_single_page_js', get_stylesheet_directory_uri() . '/includes/js/product-single.js', array(), THEME_VERSION, true);
	} 
	
	if ( is_product_category() ) {
		wp_enqueue_style( 'product_parent_page-styles', get_stylesheet_directory_uri().'/includes/css/woo-products-page-parent.css', array(), THEME_VERSION ); 
	}
	
	if ( is_account_page() || is_page( 507222 ) ) {
		wp_enqueue_style( 'woocommerce_account_page-styles', get_stylesheet_directory_uri().'/includes/css/woo-account-page.css', array(), THEME_VERSION ); 
	}
	
	if ( is_cart() ) {
		wp_enqueue_style( 'woocommerce_cart_page-styles', get_stylesheet_directory_uri().'/includes/css/woo-cart-page.css', array(), THEME_VERSION ); 
		wp_enqueue_script( 'basket_js', get_stylesheet_directory_uri() . '/includes/js/basket.js', array(), THEME_VERSION, true);
	}
	
	if ( is_checkout() ) {
		wp_enqueue_style( 'woocommerce_checkout_page-styles', get_stylesheet_directory_uri().'/includes/css/woo-checkout.css', array(), THEME_VERSION ); 
	}
	
	if ( et_core_is_builder_used_on_current_request() ) { // Check if Divi builder is used on a page, if so, call in these styles
		wp_enqueue_style( 'divi_page-styles', get_stylesheet_directory_uri().'/includes/css/divi-pages.css', array(), THEME_VERSION ); 
	}

} 

/**
 * functions.php & /includes/shop-functions/*.php contents
 *
 *
 ****** functions.php ******
 * check_if_shop_page() -- Enqueues product parent css 
 * hfc_section($container_name, $template) -- Wraps template part in container divs
 * update_cart_total_if_wholesaler() -- Updates the cart total for wholesalers as the wholesale plugin ignores the add ons
 * assign_form_to_products_in_category() -- Utility function to quickly assign gravity forms to products
 * bulk_assign_form_to_products_in_category() -- Same as above but this time you can do multiple categories with different forms
 * update_product_descriptions_in_category() -- Utility function to allow for the update of descriptions across entire categories
 * modify_search_query() -- Changes search query to 'post' instead of product if user searches the knowledge base
 * redirect_single_product_category() -- If a category only has one product in it, redirect the user to the product
 * add_query_vars_filter() -- Add page ID to the query vars
 * my_acf_op_init() -- Registers ACF options pages
 * is_product_in_category_or_child($product_id, $categories_to_check) -- Checks if a product is in a category or any of its parents
 * woocommerce_my_account_my_orders_actions() - Adds order again button to order page
 * 
 ****** /includes/shop-functions/*.php ******
 * 
 * add_ceremonial_flag_sku_to_order_meta.php -- Adds the partcode to the product order meta if it exists on the relevant DB table
 * add_coffin_drapes_sku_to_order_meta.php -- Adds the partcode to the product order meta if it exists on the relevant DB table
 * add_national_flag_sku_to_order_meta.php -- Adds the partcode to the product order meta if it exists on the relevant DB table
 * add_product_size_and_weight_to_cart_totals.php -- This one needs a fair bit more work but essentially it looks at the cart and sets eachs products cart and weight in the Woo Cart data array. This is needed for custom products and any product using a gravity form. Works fine for custom products and national flag (DB lookup), however to me made somewhat scalable, inside here we need an array of products and their dimensions to use if they're not a custom product or national flag. Currently any product using a gracity form that isn't a national flag won't have weight or size passed in
 * add_related_products_tab_to_product_data.php -- Adds related products to single products on front end, also enables the ability for them to explicity set these products on the single product editor OR the product category page
 * calculate_total_products.php -- Enables the live update of pricing on the single product page as the user updates selctions or quantaties
 * ceremonial_flags_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * coloured_bunting_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * create_part_code_fields_for_variable_products.php -- Adds some part code fields on variable product options
 * create_xml_and_save_locally.php -- Creates and saves xml file to site on successful order. Also uploads file to remote location to use in their CRM software
 * custom_banners_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * custom_bunting_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * custom_burgees_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * custom_car_flags_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * custom_forestay_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * custom_golf_pin_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * custom_handwaver_standard_size_scripts.php -- Enqueues the custom scripts and styles for this product/category
 * custom_material_handwaver_scripts.php -- Enqueues the custom scripts and styles for this product/category
 * custom_printed_flags_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * discounts-array.php -- Houses the array of products/categories that have discounts applied to them
 * get_cart_volume.php -- Fetches the volume of the cart
 * get_cart_weight.php -- Fetches the weight of the cart
 * get_custom_products_cart_weight.php -- Fetches the weight of the cart - DEPRECATED (I THINK)
 * global-discounts-function.php -- Function to handle the discounts and apply them when a product is added to the cart
 * national_flag_scripts_and_styles.php -- Enqueues the custom scripts and styles for this product/category
 * product-category-page-filter.php -- Adds the A - Z filter on category pages, simply add the cat ID to the cat array to get this working on a category
 * product-category-sorting.php -- Function to set the default sorting order of the posts
 * set_minimum_price_for_burgees.php -- Adds a surcharge (essentially a minimum price) for this product
 * set_minimum_price_for_custom_material_handwavers.php -- Adds a surcharge (essentially a minimum price) for this product
 * set_minimum_price_for_custom_printed_flags.php -- Adds a surcharge (essentially a minimum price) for this product
 * set_minimum_quantity_for_handwavers.php -- Still a WIP, but will set a minimum quantity for this product
 * set_subcategory_thumbnail_images.php -- If a category doesn't have an image set, this function looks at the products inside the cat and fetches a product image to use as the cat image
 * show_sewn_category_on_product_page.php -- This function shows the price group for any product with a sewn pricing category e.g. 'National Flags Price B'
 * update_cart_total_for_wholesalers.php -- This function updates the cart total for wholesalers for gravity form product (which the plugin doesn't handle)
 * update_flexible_shipping_cart_volume.php -- This function passes in the shipping volume to the Flexible Shipping plugin so it can accurately calculate shipping costs
 * update_flexible_shipping_cart_weight.php -- This function passes in the shipping weight to the Flexible Shipping plugin so it can accurately calculate shipping costs
 * variable_product_discounts.php -- This function handles discounts on variable products, so that the price updates (with discount applied) as the user updates their selection/quanitity
 * 
 * 
 */


add_action( 'template_redirect', 'check_if_shop_page' );

function check_if_shop_page() {

	if( is_shop() ) :

		 wp_enqueue_style( 'product_parent_page-styles', get_stylesheet_directory_uri().'/includes/css/woo-products-page-parent.css', array(), THEME_VERSION ); 

	endif;    
}

function hfc_section( $container_name = "", $template ) {
	?>

		<section id="<?= $container_name ?>" class="hfc-container">
						
			<div class="hfc-row">
				
				<?php get_template_part($template) ?>
				
			</div>

		</section>	

	<?php
}

function assign_form_to_products_in_category() {
    // Set the IDs of the product categories you want to assign the form to
    $category_ids = array( 130, 211 );
    // Get all products in the specified categories
    $args = array(
        'post_type' => 'product',
		'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_ids,
                'operator' => 'IN',
            )
        )
    );
    $product_ids = get_posts( $args );
    // Assign form ID to all products in the specified categories
    print_r($product_ids);
    foreach ( $product_ids as $product_id ) {
        $form_data = array(
            'id' => 16
        );
        //update_post_meta( $product_id, '_gravity_form_data', $form_data );
    }
}

//add_action( 'init', 'assign_form_to_products_in_category' );
//


function bulk_assign_form_to_products_in_category() {

	$categories = [
		"a" => [
			"cats" => array( 742, 653, 621, 36, 667, 217 ),
			"form" => 6
		],
		"b" => [
			"cats" => array( 637, 226, 681, 707, 622, 619, 38, 370, 744, 666, 683, 207 ),
			"form" => 8
		],
		"c" => [
			"cats" => array( 607, 224, 656, 600, 728, 37, 665, 586, 694, 215 ),
			"form" => 9
		],
		"d" => [
			"cats" => array( 213, 687, 705, 225, 662, 743, 601, 669, 32, 692 ),
			"form" => 10
		],
		"e" => [
			"cats" => array( 205, 719, 734, 736, 703, 223, 635, 724, 732, 696, 675, 645, 35, 716, 657, 677, 739, 709, 661, 594, 697, 623 ),
			"form" => 11
		],
		"f" => [
			"cats" => array( 700, 706, 220, 199, 611, 658, 609, 668, 33, 729, 714, 640, 588, 625, 204 ),
			"form" => 12
		],
		"g" => [
			"cats" => array( 725, 702, 704, 222, 196, 731, 648, 711, 643, 596, 628, 26, 559, 650, 368, 722, 616, 685, 589, 627, 202 ),
			"form" => 13
		],
		"gx1.25" => [
			"cats" => array( 745, 689, 672, 691 ),
			"form" => 32
		],
		"gx1.5" => [
			"cats" => array( 727, 670 ),
			"form" => 34
		],
		"gx2" => [
			"cats" => array( 674, 690, 592 ),
			"form" => 35
		],
		"gx2.5" => [
			"cats" => array( 754 ),
			"form" => 36
		],
		"h" => [
			"cats" => array( 733, 738, 660, 632, 713, 659, 598, 655, 34, 740, 680, 614, 590 ),
			"form" => 14
		],
		"j" => [
			"cats" => array( 130, 211, 617, 698 ),
			"form" => 16
		],
		"no-sewn" => [
			"cats" => array( 726 ),
			"form" => 17
		]
	];

	foreach($categories as $cat) {

		$category_ids = $cat['cats'];

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'fields' => 'ids',
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $category_ids,
					'operator' => 'IN',
				)
			)
		);

		$product_ids = get_posts( $args );
		
		//echo '<pre>' .  print_r($product_ids, true) . '</pre>';

		// Assign form ID to all products in the specified categories
		foreach ( $product_ids as $product_id ) {
			$form_data = array(
				'id' => $cat['form']
			);
			update_post_meta( $product_id, '_gravity_form_data', $form_data );
			wp_update_post( array( 'ID' => $product_id ) );
		}

	}
	
}

//add_action( 'init', 'bulk_assign_form_to_products_in_category' );

function update_product_descriptions_in_category() {
    // Define the category slug you want to update product descriptions for
    $category_slug = 'american-flags-price-e';

    // Get the category ID from the slug
    $category = get_term_by('slug', $category_slug, 'product_cat');

    // Get all products in the category
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category->term_id,
            ),
        ),
    );
    $products = new WP_Query($args);

    // Loop through the products and update their descriptions
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();
            $new_description = 'Manufactured in the UK and using Ministry of Defence approved materials, our flags provide nothing but excellence and quality. We supply flags for any purpose, including exterior flagpoles, sporting events, and indoor displays.

Flags are manufactured and offered in several materials:

•        Sewn – These flags are the preferred option of the MOD, and made using pre-coloured 155gsm Woven Polyester material. The flag is created by our team sewing the different coloured fabrics together to create the flag design. Where intricate badges on the flag is required, a printed badge will be sewn to the flag. This flag provided the most traditional look and feel.
•        Printed – These flags are digitally printed onto 115gsm Knitted Polyester material.
•        Eco Friendly – These flags are digitally printed onto 115gsm Recycled Knitted Polyester material.
•        Novelty – These flags are printed onto lightweight Polyester material and manufactured as decorative flags only and not be flown on a flagpole.

All flags come hemmed and finished to your requirements, ready to fly or hang.

Antifray netting can be added to the fly edge of your flag. This can prolong the life of your flag as it is a sacrificial piece of material that is applied and will be the first part of the flag to fray. Once you notice this fray, it is time to take your flag down send it back to us to have the antifray removed and replaced. This is then a lot cheaper than purchasing a whole new flag.';
			wp_update_post( array('ID' => $product_id, 'post_content' => $new_description) );
			wp_update_post( array('ID' => $product_id, 'post_excerpt' => $new_description ) );
        }
        wp_reset_postdata();
    }
}
//add_action( 'init', 'update_product_descriptions_in_category' );

function is_product_in_category_or_parent($product_id, $categories_to_check) {
    // get product categories
    $product_cats = wp_get_post_terms($product_id, 'product_cat');

    foreach ($product_cats as $product_cat) {
        if (in_array($product_cat->slug, $categories_to_check)) {
            // product is in desired category
            return true;
        } else {
            // get parent categories and check them
            $parent_cat_id = $product_cat->parent;
		
            while ($parent_cat_id != 0) {
                $parent_cat = get_term($parent_cat_id, 'product_cat');

                if (in_array($parent_cat->slug, $categories_to_check)) {
                    return true;
                }

                $parent_cat_id = $parent_cat->parent;
            }
        }
    }
    // no match found
    return false;
}

add_filter( 'woocommerce_my_account_my_orders_actions', 'bbloomer_order_again_action', 9999, 2 );
    
function bbloomer_order_again_action( $actions, $order ) {
    if ( $order->has_status( 'completed' ) ) {
        $actions['order-again'] = array(
            'url' => wp_nonce_url( add_query_arg( 'order_again', $order->get_id(), wc_get_cart_url() ), 'woocommerce-order_again' ),
            'name' => __( 'Order again', 'woocommerce' ),
        );
    }
    return $actions;
}

function redirect_single_product_category() {
    if (is_product_category()) {
        $term = get_queried_object();
        $product_args = array(
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => $term->taxonomy,
                    'field' => 'slug',
                    'terms' => $term->slug
                )
            )
        );
        $products = new WP_Query($product_args);
        if ($products->have_posts()) {
            if ($products->post_count == 1) {
                wp_redirect(get_permalink($products->posts[0]->ID));
                exit();
            }
        }
    }
}
add_action('template_redirect', 'redirect_single_product_category');

function modify_search_query( $query ) {
    if ( !is_admin() && $query->is_search() && is_page( 'knowledge-base' ) ) {
        $query->set( 'post_type', 'post' );
    }
}
//add_action( 'pre_get_posts', 'modify_search_query' );

function add_query_vars_filter( $vars ){
  $vars[] = "page_id";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

// Create ACF Global options page
add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {

    // Check function exists.
    if( function_exists('acf_add_options_page') ) {

       $option_page = acf_add_options_page(array(
            'page_title'    => __('HFC Global Options'),
            'menu_title'    => __('HFC Global Options'),
            'menu_slug'     => 'global-options',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
		
    }
	
}



// Add sale text to the existing 'onsale' banner
function add_sale_text_to_onsale_div() {
    if (is_product()) {
        global $product;

        $sale_text = get_field('sale_text', $product->get_id());
        if ($product->is_on_sale() && !empty($sale_text)) { ?>
            <script>
                jQuery(document).ready(function($) {
                    var existingContent = $(".onsale").html();
                    var newText = existingContent + " - <?= esc_js($sale_text); ?>";
                    $(".onsale").html(newText);
                });
            </script>
			<style>
				.onsale {
					text-decoration: none!important;
				}
			</style>
        <?php }
    }
}

add_action('woocommerce_before_single_product', 'add_sale_text_to_onsale_div');

// Add Nett Option
function product_nett() {

    if (is_product()) {

        $nett_value = get_field('nett');

        if ($nett_value === true) { ?>
               <style>
				   .content-area .product .price .starting-from .amount:after {
						content: 'nett';
					    margin-left: 5px;
					}
				   .content-area .product .total-calculated-price:after {
						content: 'nett';
					    margin-left: 5px;
					}			   
               </style>
        <?php }
    }
}

add_action('wp', 'product_nett');

function hide_feefo() {
    $current_page_id = get_the_ID();

    if ( $current_page_id === 509343 ) { ?>
        <style>
        	#feefo-service-review-floating-widgetId { display: none!important; }
		</style>
    <?php }
}
add_action( 'wp_head', 'hide_feefo' );

?>
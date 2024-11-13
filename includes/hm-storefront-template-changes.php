<?php

function remove_actions_parent_theme() {
	remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
	remove_action( 'storefront_header', 'storefront_product_search', 40 );
	remove_action( 'storefront_header', 'storefront_header_cart', 60 );
	remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
	remove_action( 'storefront_footer', 'storefront_credit', 20 );
	remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
};

add_action( 'init', 'remove_actions_parent_theme', 1);

add_action( 'storefront_header', 'storefront_product_search', 35 );
add_action( 'storefront_header', 'hm_header_tel', 38 );
//add_action( 'storefront_header', 'hm_header_account', 39 );
add_action( 'storefront_header', 'storefront_header_cart', 40 );
add_action( 'storefront_before_header', 'storefront_add_topbar', 5 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_breadcrumb', 10 );
add_action( 'woocommerce_before_single_product', 'woocommerce_breadcrumb', 10 );
add_action( 'woocommerce_archive_description', 'add_image_to_category_page_header', 10 );

function move_upsells_below_summary() {
    remove_action( 'woocommerce_after_single_product_summary', 'storefront_upsell_display', 15 );
    add_action( 'woocommerce_after_single_product', 'storefront_upsell_display', 15 );
}
add_action( 'init', 'move_upsells_below_summary' );

// remove the subcategories from the product loop
remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

// add subcategories before the product loop (yet after catalog_ordering and result_count -> see priority 40)
add_action( 'woocommerce_before_shop_loop', 'wp56123_show_product_subcategories', 40 );

function wp56123_show_product_subcategories() {
    $subcategories = woocommerce_maybe_show_product_subcategories();
        if ($subcategories) {
          echo '<ul class="subcategories">',$subcategories,'</ul>';
    }
}

function storefront_page_header() {
	if ( is_front_page() && is_page_template( 'template-fullwidth.php' ) ) {
		return;
	}
	
	if ( et_core_is_builder_used_on_current_request() ) {
		return;
	}

	?>
	<header class="entry-header">
		<?php
		storefront_post_thumbnail( 'full' );
		the_title( '<h1 class="entry-title">', '</h1>' );
		?>
	</header><!-- .entry-header -->
	<?php
}

function add_image_to_category_page_header() {
	$term_id = get_queried_object_id();
	$image_id = '';

	// Check if the banner image field exists
	if (get_field('banner_image', 'category_' . $term_id)) {
		$image_id = get_field('banner_image', 'category_' . $term_id)['id'];
	} else {
		// If banner image field is not set, use the thumbnail image
		$thumbnail_id = get_term_meta($term_id)['thumbnail_id'][0];
		if ($thumbnail_id) {
			$image_id = $thumbnail_id;
		} else {
			// If neither banner image nor thumbnail image is available, use a default image
			$image_id = 507335;
		}
	}

	$image = wp_get_attachment_image($image_id, "large", true, array("loading" => "lazy"));
	echo $image;
}


//Remove zoom from product images
add_filter( 'woocommerce_single_product_zoom_options', 'custom_single_product_zoom_options' );
function custom_single_product_zoom_options( $zoom_options ) {
    // Changing the magnification level:
    $zoom_options['magnify'] = 0;

    return $zoom_options;
}

/**
 * Adds a top bar to Storefront, before the header.
 */
function storefront_add_topbar() {
    ?>
    <div id="topbar">
        <div class="col-full">
			
			<a class="feefo" target="_blank" href="https://www.feefo.com/en-GB/reviews/hampshire-flag?displayFeedbackType=BOTH&timeFrame=YEAR" rel="nofollow">
				<?= wp_get_attachment_image( 507369, "large", true, array( "loading" => "lazy" ) ); ?>
				<p>4.8 / 5</p>
				<div class="stars">
					<?php for($i = 0; $i < 5; $i++) : ?>
						<span class='star'></span>
					<?php endfor; ?>
				</div>
			</a>
			
            <?= wp_nav_menu(array('theme_location' => 'secondary' )); ?>
			<?= do_shortcode('[vatToggle]'); ?>
        </div>
    </div>
    <?php
}

/**
*
* Displayed a link to the account page if logged in, login page if not
*
* @return void
* @since  1.0.0
*/

function hm_header_account() {
	
	?>

		<a class="account-link" href="/my-account">
			
			<div class="account-head"></div>
			<div class="account-body"></div>
			
		</a>

	<?php
}

/**
*
* Displayed the HFC phone Number
*
* @return void
* @since  1.0.0
*/

function hm_header_tel() {

	?>

		<div class="tel"><a href="tel:02392 237130"><p>Tel 02392 237130</p></a></div>

	<?php
}

/**
* Modifyed Cart Link to remove subtotal and 'item/items' from product count
* Displayed a link to the cart including the number of items present and the cart total
*
* @return void
* @since  1.0.0
*/

function storefront_cart_link() {
	
	if ( ! storefront_woo_cart_available() ) return; 

	?>

		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'storefront' ); ?>">
			
			<img src="<?= get_stylesheet_directory_uri() . '/assets/cart.png' ?>" alt="Cart Icon">
			
			<?php /* translators: %d: number of items in cart */ ?>
			<span class="count"><?php echo wp_kses_data( sprintf( _n( '%d', '%d', WC()->cart->get_cart_contents_count(), 'storefront' ), WC()->cart->get_cart_contents_count() ) ); ?></span>
		</a>
	<?php
}

/**
* Remove right-sidebar class from body class list
*
*/

add_filter( 'body_class', 'remove_right_sidebar_class', 50, 2);
function remove_right_sidebar_class( $classes ) {
    // Remove 'right-sidebar' class
    if (in_array('right-sidebar', $classes)) {
        unset( $classes[array_search('right-sidebar', $classes)] );
    }

    return $classes;
}

/**
* Change Button Text on shop page
*
*/

add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text', 10, 2 );

function woocommerce_custom_product_add_to_cart_text( $text, $product ) {
	//global $product;
	$gravity_form_data = get_post_meta(get_the_ID(), '_gravity_form_data');

	if($gravity_form_data) return __( 'Shop Product', 'woocommerce' );
    if ( $text == __( 'Select options', 'woocommerce' )) return __( 'Shop Product', 'woocommerce' );
  	if (is_product()) return $text;
	
	$product_id = $product->get_id();
    $product_url = get_permalink( $product_id );
    $product_title = $product->get_name();
	
	$view_details_button = '<a href="' . esc_url( $product_url ) . '" class="button add_to_cart_button" aria-label="' . esc_attr__( 'View &ldquo;', 'woocommerce' ) . $product_title . esc_attr__( '&rdquo; details', 'woocommerce' ) . '" rel="nofollow">' . __( 'Shop Product', 'woocommerce' ) . '</a>';
    echo $view_details_button;
	
	return $text;
}



//Remove title hook and add in a new one with the product categories added
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'VS_woo_loop_product_title', 10 );

function VS_woo_loop_product_title() {
    
    $terms = get_the_terms( $post->ID, 'product_cat' );
    if ( $terms && ! is_wp_error( $terms ) ) :
    
		//only displayed if the product has at least one category
        ?>

		<p class="subheading"><?= $terms[0]->name ?></p>
        
    <?php endif;
	
	echo '<h3 class="woocommerce-loop-product__title">' . get_the_title() . '</h3>';
}

//Add opening div which finishes in the function below
add_action( 'woocommerce_before_subcategory', 'div_before_image', 10 );
function div_before_image() {
	?>
<div>	
<?php
}

//Add Catergory meta after the title on the blocks on the category pages
add_action( 'woocommerce_after_subcategory_title', 'category_block_meta', 10 );

function category_block_meta($category) {
    $cat_id = $category->term_id;
	$cat_desc = $category->description;
	$cat_desc_length = str_word_count($cat_desc);
	$cat_desc_limit = 25;
	$cat_link = get_the_permalink($cat_id);
	$cat_name = $category->name;

	$args = array(
		'category' => array($category->slug),
	);

	$products = wc_get_products($args);

	$products_with_price = array_filter($products, function($product) {
		return $product->get_price() !== '' && $product->get_price() !== '0';
	});

	if (empty($products_with_price)) {
		$price_var = '';
	} else {
		usort($products_with_price, function($a, $b) {
			return $a->get_price() - $b->get_price();
		});

		$lowest_priced_product = reset($products_with_price);
		$price_var = $lowest_priced_product->get_price();
	}

    // Check if user is logged in
    if ( is_user_logged_in() ) {
        // Check if user has the 'wholesale' role
        if ( current_user_can( 'wcwp_wholesale' ) ) {
            // Get price excl. tax
            $price_var = number_format($product->get_price_excluding_tax(), 2, ".", ",");
        }
    }
    ?>
		</div>
			<div class="category">

				<div>
					<h2><?= $cat_name ?></h2>
					<?php if($cat_desc) : ?>
						<?php if($cat_desc_length > $cat_desc_limit) : ?>
							<p><?= limit_text($cat_desc, $cat_desc_limit) ?>...</p>
						<?php else : ?>
							<p><?= $cat_desc ?></p>
						<?php endif; ?>
					<?php endif; ?>
					<?php if($price_var) : ?>
						<p class="starting-from">Starting from £<span class="product-price"><?= $price_var ?></span></p>
					<?php endif; ?>
				</div>
				
				<div class="primary-btn">Shop Now</div>

			</div>
    <?php
}


add_action( 'woocommerce_after_main_content', 'show_feefo_section', 10 );
function show_feefo_section() {
	hfc_section('feefo-small-widget', 'template-parts/global-feefo-large' );
}


// Calculate the product price and store it in a global variable
function calculate_product_price() {
    global $product, $product_price;

    $is_variable_product = $product && ! empty( $product ) ? $product->is_type( 'variable' ) : false;
    $product_price = $is_variable_product ? $product->get_variation_price( 'min', true ) : $product->get_price();
}

add_action( 'woocommerce_before_single_product_summary', 'calculate_product_price' );

// Display the product price using the calculated price
function display_product_price() {
    global $product, $product_price;
	$gravity_form_data = get_post_meta(get_the_ID(), '_gravity_form_data');
	
    $display_price = '<span class="price">';
    if ( $product && ! empty( $product ) && $product->is_type( 'variable' ) ) {
		
		// If product is in flagpole category (not accessories) add 'nett' after the price
		$category_ids = array(72, 92, 103, 1718, 94);
		$belongs_to_specific_category = false;
		foreach ($category_ids as $category_id) {
			if (has_term($category_id, 'product_cat', $product->get_id())) {
				$belongs_to_specific_category = true;
				break;
			}
		}
		$nett = ( $belongs_to_specific_category == true ) ? ' nett' : '';
		
        $display_price .= '<span class="starting-from">Starting from £<span class="amount">'. number_format( $product_price, 2, ".", "," ) .'</span>'. $nett .'</span>';
    } elseif ($gravity_form_data) {
		 $display_price .= '<span class="starting-from">Starting from £<span class="amount">'. number_format( $product_price, 2, ".", "," ) .'</span></span>';
	} else {
        $display_price .= '<span class="amount">£'. number_format( $product_price, 2, ".", "," ) .'</span>';
    }
    $display_price .= '</span>';

    if ( $product_price != 0.00 ) {
        echo $display_price;
    }
}

add_action( 'woocommerce_single_product_summary', 'display_product_price', 11 );


function edit_price_display($price) {
	
	global $product;
	$gravity_form_data = get_post_meta(get_the_ID(), '_gravity_form_data');
	//$is_variable_product = $product->is_type( 'variable' );
	
	//if($is_variable_product) return;
	if( is_page( 507214 ) ) return $price;
	if( is_singular('product') ) return;
	
	$price = number_format($product->price, 2, ".", ",");
	
	//echo '<pre>' . print_r( $product->is_type( 'variable' ), true) . '</pre>';
	//echo '<pre>' . print_r($gravity_form_data, true) . '</pre>';
	
	$display_price = '<span class="price">';
	if ( $product && ! empty( $product ) && $product->is_type( 'variable' ) ) {
        $display_price .= '<span class="starting-from">Starting from £<span class="amount">'. number_format( $product->get_variation_price( 'min', true ), 2, ".", "," ) .'</span></span>';
    } elseif($gravity_form_data) {
		$display_price .= '<span class="starting-from">Starting from £<span class="amount">'. $price .'</span></span>';
	} else {
		$display_price .= '<span class="amount">£'. $price .'</span>';
	}
	$display_price .= '</span>';
	
	if($price != 0.00) echo $display_price;
	
}

add_filter('woocommerce_get_price_html', 'edit_price_display', 10, 2);

add_filter('woocommerce_available_variation', 'edit_selected_variation_price', 10, 3);

function edit_selected_variation_price( $data, $product, $variation ) {
    $price = $variation->price;
	$original_price = number_format($price, 2, ".", ",");
    $price = number_format($price, 2, ".", ",");
	
	if ( is_user_logged_in() ) {
        // Check if user has the 'wholesale' role
        if ( current_user_can( 'wcwp_wholesale' ) ) {
            // Get price excl. tax
            $price = number_format($variation->get_price_excluding_tax(), 2, ".", ",");
        }
	}
    $display_price = '<span class="price">';
	
	if ( is_user_logged_in() ) {
        // Check if user has the 'wholesale' role
        if ( current_user_can( 'wcwp_wholesale' ) ) {
            // Get price excl. tax
            $display_price .= '<span class="starting-from inc-vat"><span class="og-price">£' . $original_price . ' inc. VAT</span><span class="amount">£'. $price .' exc. VAT</span></span>';
        } else {
			$display_price .= '<span class="starting-from inc-vat"><span class="amount">£'. $price .'</span></span>';
		}
	} else {
		$display_price .= '<span class="starting-from inc-vat"><span class="amount">£'. $price .'</span></span>';
	}
	
    $display_price .= '</span>';
    $data['price_html'] = $display_price;

    return $data;
}

function iconic_wc_ajax_variation_threshold( $qty, $product ) {
    return 1000;
}
 
add_filter( 'woocommerce_ajax_variation_threshold', 'iconic_wc_ajax_variation_threshold', 10, 2 );

// Function to add custom HTML content to pages with the 'term-bunting' class
function add_custom_html_to_term_bunting() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Check if the body has the 'term-bunting' class
            if (document.body.classList.contains('term-bunting')) {
                // Define the HTML content to add
                var htmlContent = '<p class="starting-from">Starting from £<span class="product-price">7.36</span></p>';
                
                // Find all div elements with the class 'category'
                var categoryDivs = document.querySelectorAll('div.category');
                
                // Check if there is at least a second 'category' div
                if (categoryDivs.length > 1) {
                    // Select the second 'category' div
                    var secondCategoryDiv = categoryDivs[1];
                    
                    // Find the <p> element within the second 'category' div
                    var pElement = secondCategoryDiv.querySelector('p');
                    
                    if (pElement) {
                        // Insert the new content after the <p> element in the second 'category' div
                        pElement.insertAdjacentHTML('afterend', htmlContent);
                    }
                }
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'add_custom_html_to_term_bunting');

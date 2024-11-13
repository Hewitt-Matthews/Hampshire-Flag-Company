<?php

//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );


////////////////////////////////////////////////////////////////////////
//
//Add Description Field to Attributes
//
////////////////////////////////////////////////////////////////////////

function my_edit_wc_attribute_my_field() {
    $id = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : 0;
    $value = $id ? get_option( "wc_attribute_attr_desc-$id" ) : '';
    ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="attr-desc">Attribute Description</label>
            </th>
            <td>
				<input style="width: 100%; max-width: 400px; padding: 7px; " name="attr_desc" id="attr-desc" value="<?php echo esc_attr( $value ); ?>" />
            </td>
        </tr>
    <?php
}
add_action( 'woocommerce_after_add_attribute_fields', 'my_edit_wc_attribute_my_field' );
add_action( 'woocommerce_after_edit_attribute_fields', 'my_edit_wc_attribute_my_field' );


function my_save_wc_attribute_my_field( $id ) {
    if ( is_admin() && isset( $_POST['attr_desc'] ) ) {
        $option = "wc_attribute_attr_desc-$id";
        update_option( $option, sanitize_text_field( $_POST['attr_desc'] ) );
    }
}
add_action( 'woocommerce_attribute_added', 'my_save_wc_attribute_my_field' );
add_action( 'woocommerce_attribute_updated', 'my_save_wc_attribute_my_field' );


add_action( 'woocommerce_attribute_deleted', function ( $id ) {
    delete_option( "wc_attribute_my_field-$id" );
} );


add_action( 'woocommerce_before_variations_form', 'add_attribute_descriptions');
function add_attribute_descriptions() {
	
	global $product;
	$product = wc_get_product($product->get_id());
	$attributes = $product->get_attributes();
	
	$description_array = [];
	
	foreach ($attributes as $attribute) {
		
		$attr_id = wc_attribute_taxonomy_id_by_name( $attribute['name'] );
		$my_field = get_option( "wc_attribute_attr_desc-$attr_id" );
		
		if($my_field) {
			$description_array[$attribute['name']] = $my_field;
		}
	}
	
	?>

	<script type="text/javascript">
		
		window.addEventListener('load', () => {
			
			//Get the descs from PHP
			let attributeDescriptions = <?= json_encode($description_array); ?>;
			
			//Get the existing atts on the page
			let attributes = document.querySelectorAll('.variations tr .label label');
			
			//Function to create the description
			const createDecription = (desc) => {
				
				const descriptionContainer = document.createElement('div');
				descriptionContainer.classList.add('description');
					const descriptionTextEl = document.createElement('p');
					descriptionTextEl.textContent = desc;
					descriptionContainer.appendChild(descriptionTextEl);
				
				return descriptionContainer;
				
			}
			
			//Fucntion to show the desc
			const toggleDescription = (e) => {
				
				const description = e.target.parentElement.nextElementSibling;	
				const descStyles = window.getComputedStyle(description);
				const descHeight = descStyles.getPropertyValue('max-height');
				
				description.classList.toggle('active');
				
				if(descHeight == "0px") {
					description.setAttribute('style', `max-height: ${description.scrollHeight}px;`);
				} else {
					description.removeAttribute('style');
				}
				
			}

			//Loop through each attribute descs
			for (const [key, value] of Object.entries(attributeDescriptions)) {

				//Loop through all atts on the page
				attributes.forEach(attribute => {

					//If the att exists that the desc exists for append it to the label
					if(key == attribute.htmlFor) {
						
						const description = createDecription(value);
						
						const infoIcon = document.createElement('span');
						infoIcon.innerText = 'i';
						
						infoIcon.addEventListener('click', toggleDescription);
						
						attribute.appendChild(infoIcon);
						attribute.after(description);
						
					}

				})
			}

			
		})
		
	</script>

	<?php
	
}

////////////////////////////////////////////////////////////////////////
//
//Customize product data tabs
//
////////////////////////////////////////////////////////////////////////

//Remove default Tabs
add_filter( 'woocommerce_product_tabs', '__return_empty_array' );


//Add artwork template to product
add_action( 'woocommerce_after_single_product', 'product_template_files_and_guides', 0 );

function product_template_files_and_guides() {
	
	get_template_part('template-parts/products/tempates_and_guides');
	
}

//Add discount table to product page
add_action( 'woocommerce_after_single_product', 'show_product_discount_table', 0 );

function show_product_discount_table() {
	?>
	<div class="product-after-element" data-dropdown="discount-table">
		<h2>Bulk Buy Prices</h2>
		<?php get_template_part('template-parts/products/discount_table'); ?>
	</div>
	<?php	
}


//add_action( 'woocommerce_after_single_product', 'show_product_dimensions', 0 );

function show_product_dimensions() {
	?>
	<div class="product-after-element" data-dropdown="product-dimensions">
		<h2>Product Dimensions</h2>
		<?php get_template_part('template-parts/products/product_dimensions'); ?>
	</div>
	<?php	
}


//Remove default related products output and re-prioritise so it's lower down
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
//add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 55 );

//Add Reviews in
add_action( 'woocommerce_after_single_product', 'add_reviews_to_summary', 45);

function add_reviews_to_summary() {
	
	global $product;
	$number_of_reviews = $product->get_review_count();
	
	?>
	
	<div class="product-after-element" data-dropdown="reviews">

		<?php if ( $number_of_reviews > 0 ) : ?>
			<h2>Reviews (<?= $number_of_reviews ?>)</h2>
		<?php else:  ?>
			<h2>Leave a review</h2>
		<?php endif; ?>
		<?php comments_template(); ?>

	</div>

	<?php
}

//Add request_a_quote in
add_action( 'woocommerce_after_single_product', 'add_request_a_quote_form_to_summary', 50);

function add_request_a_quote_form_to_summary() {
	?>

	<style>
	
		.raq-container {
			display: flex;
			flex-wrap: wrap;
			gap: 2em;
		}
		
		.raq-container .gform_wrapper {
			flex: 1 1 500px;
		}
		
		#page #primary .phone-and-email > div .icon-container {
			height: min-content;
		}
		
		.raq-container .gform_wrapper input:not(input[type="submit"]),
		.raq-container .gform_wrapper textarea {
			background-color: #fff;
			border: solid 1px #000!important;
			box-shadow: none;
		}

	</style>

	<div class="product-after-element">
		
		<h2>Request a quote</h2>
		<div>
			
			<div class="raq-container">
			
				<?= do_shortcode('[gravityforms id="5" title="false" ajax="true"]'); ?>

				<div>

					<?php get_template_part('template-parts/contact/phone-and-email'); ?>

				</div>
				
			</div>
			
		</div>
		
	</div>

	<?php
}

//Create Description Tab
function get_description_tab() {
	
	global $post;
	$args = array( 'taxonomy' => 'product_cat',);
	$terms = wp_get_post_terms($post->ID,'product_cat', $args);
	
	global $product;
	$description = $product->get_description();

	?>

	<?php if($terms[0]->description) : ?>

<!-- 	If an individual cafe barrier then dont show description of Category	 -->
		<?php if ( $post->ID !== 509343 ) : ?>

			<div class="product-description">

				<h2><?= $terms[0]->name ?></h2>
				<?= $terms[0]->description ?>
				<!-- 		<p><?php// echo $description ?></p> -->

			</div>
		
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( $product->is_type( 'simple' ) ) :
			if($product->get_length()) :
	?>

				<div class="product-after-element">

					<h2>Specification</h2>
					<div>
						<?php wc_display_product_attributes($product); ?>
					</div>

				</div>

			<?php endif; ?>

	<?php elseif($product->is_type( 'variable' )) : ?>

		<div class="product-after-element">

			<h2>Specification</h2>
			<div>
				<?php
				add_filter( 'woocommerce_product_get_weight', '__return_false' );
				add_filter( 'woocommerce_product_get_dimensions', '__return_false' );

				// Get product variations
				$variations = $product->get_available_variations();

				// Initialize variables for weight and dimensions
				$weight_min = '';
				$weight_max = '';
				$dimensions_min = '';
				$dimensions_max = '';

				// Loop through each variation to find the minimum and maximum weight and dimensions
				foreach ($variations as $variation) {
					$variation_id = $variation['variation_id'];
					$variation_obj = wc_get_product($variation_id);
					$variation_weight = $variation_obj->get_weight();
					$variation_dimensions = $variation_obj->get_dimensions();

					if ($variation_weight) {
						if ($weight_min == '' || $variation_weight < $weight_min) {
							$weight_min = $variation_weight;
						}

						if ($weight_max == '' || $variation_weight > $weight_max) {
							$weight_max = $variation_weight;
						}
					}

					if ($variation_dimensions) {
						$variation_dimensions_arr = explode(' x ', $variation_dimensions);

						// If we have all three dimensions (length, width, and height)
						if (count($variation_dimensions_arr) == 3) {
							$variation_dimensions_total = $variation_dimensions_arr[0] * $variation_dimensions_arr[1] * $variation_dimensions_arr[2];
						} else if (count($variation_dimensions_arr) == 2) {
							$variation_dimensions_total = $variation_dimensions_arr[0] * $variation_dimensions_arr[1];
						} else {
							$variation_dimensions_total = $variation_dimensions_arr[0];
						}

						if ($dimensions_min == '' || $variation_dimensions_total < $dimensions_min) {
							$dimensions_min = $variation_dimensions_total;
						}

						if ($dimensions_max == '' || $variation_dimensions_total > $dimensions_max) {
							$dimensions_max = $variation_dimensions_total;
						}
					}
				}

				// Calculate dimensions range
				if ($dimensions_min != '' && $dimensions_max != '') {
					$dimensions_range = $dimensions_min . ' to ' . $dimensions_max . ' ' . get_option('woocommerce_dimension_unit');
				} else {
					$dimensions_range = 'N/A';
				}
	
				?>

				<table class="woocommerce-product-attributes shop_attributes">
					<tbody>
						<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--weight">
							<th class="woocommerce-product-attributes-item__label">Weight</th>
							<td class="woocommerce-product-attributes-item__value"><?= wc_format_weight($weight_min) . ' to ' . wc_format_weight($weight_max) ?></td>
						</tr>
						<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--dimensions">
							<th class="woocommerce-product-attributes-item__label">Dimensions</th>
							<td class="woocommerce-product-attributes-item__value"><?php echo esc_html( $dimensions_range ); ?></td>
						</tr>
						<?php wc_display_product_attributes( $product ); ?>
					</tbody>
				</table>

			</div>

		</div>

	<?php endif; ?>

	<?php
	
}


add_filter( 'woocommerce_after_single_product', 'init_custom_tabs', 5 );

function init_custom_tabs() {
	
	get_description_tab();

}
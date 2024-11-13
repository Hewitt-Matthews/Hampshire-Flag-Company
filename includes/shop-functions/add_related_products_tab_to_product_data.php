<?php 

// Add new term meta field
function add_related_products_field_to_category($term) {
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="related_ids"><?php _e('Related Products', 'woocommerce'); ?></label></th>
        <td>
            <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="related_ids" name="related_ids[]" data-sortable="true" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woocommerce'); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval($term->term_id); ?>">
                <?php
                $product_ids = get_term_meta($term->term_id, '_related_ids', true);
                if (!empty($product_ids)) {
                    foreach ($product_ids as $product_id) {
                        $product = wc_get_product($product_id);
                        if (is_object($product)) {
                            echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <?php
}
add_action('product_cat_edit_form_fields', 'add_related_products_field_to_category', 10, 2);
add_action('product_cat_add_form_fields', 'add_related_products_field_to_category', 10, 2);

// Save term meta field
function save_related_products_field_to_category($term_id) {
    if (isset($_POST['related_ids'])) {
        update_term_meta($term_id, '_related_ids', $_POST['related_ids']);
    } else {
        delete_term_meta($term_id, '_related_ids');
    }
}
add_action('edited_product_cat', 'save_related_products_field_to_category', 10, 2);
add_action('create_product_cat', 'save_related_products_field_to_category', 10, 2);

function add_related_products_field() {
    global $post, $product_object;

    if (!$product_object) {
        $product_object = wc_get_product($post->ID);
    }

    ?>
    <p class="form-field">
        <label for="related_ids"><?php _e('Related Products', 'woocommerce'); ?></label>
        <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="related_ids" name="related_ids[]" data-sortable="true" data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woocommerce'); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval($post->ID); ?>">
            <?php
            $product_ids = $product_object->get_meta('_related_ids', true);

            foreach ($product_ids as $product_id) {
                $product = wc_get_product($product_id);
                if (is_object($product)) {
                    echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . wp_kses_post($product->get_formatted_name()) . '</option>';
                }
            }
            ?>
        </select> <?php echo wc_help_tip(__('Related products are products you recommend as well as or instead of the currently viewed product, these products typically are linked in some way to the viewed product, for example, a flag may have a flag pole as a related product or a different flag.', 'woocommerce')); ?>
    </p>
    <?php
}
add_action('woocommerce_product_options_related', 'add_related_products_field');

function save_related_products_field($product) {
    if (isset($_POST['related_ids']) && !empty($_POST['related_ids'])) {
        $product->update_meta_data('_related_ids', $_POST['related_ids']);
    } else {
        $product->delete_meta_data('_related_ids');
    }
}
add_action('woocommerce_admin_process_product_object', 'save_related_products_field');


function my_custom_related_products_output() {
    global $product;

    // Get the custom related products
    $related_products = $product->get_meta('_related_ids');

    // If the custom related products do not exist, get the related products from the product category
    if (empty($related_products)) {
        // Get the product categories
        $product_cats = wp_get_post_terms( $product->get_id(), 'product_cat' );
        
        if (!empty($product_cats)) {
            // Let's just take the first product category
            $first_product_cat = $product_cats[0];
            
            // Get the related products from the category
            $related_products = get_term_meta($first_product_cat->term_id, '_related_ids', true);
			
			// Create a new WC_Product_Query
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => 4,
				'post__in' => $related_products,
				'orderby' => 'rand',
			);

			// The custom related products query
			$products = new WP_Query($args);

			// Display your custom related products
			if ($products->have_posts()) {
				echo '<section class="related products">';
				echo '<h2>' . esc_html__('Related products', 'woocommerce') . '</h2>';
				echo '<ul class="products columns-4">';

				while ($products->have_posts()): $products->the_post();
					wc_get_template_part('content', 'product');
				endwhile;

				echo '</ul>';
				echo '</section>';
			}
			wp_reset_postdata(); 
			
        }
    } else if (!empty($related_products)) {
        // Create a new WC_Product_Query
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'post__in' => $related_products,
            'orderby' => 'rand',
        );

        // The custom related products query
        $products = new WP_Query($args);

        // Display your custom related products
        if ($products->have_posts()) {
            echo '<section class="related products">';
            echo '<h2>' . esc_html__('Related products', 'woocommerce') . '</h2>';
            echo '<ul class="products columns-4">';

            while ($products->have_posts()): $products->the_post();
                wc_get_template_part('content', 'product');
            endwhile;

            echo '</ul>';
            echo '</section>';
        }
        wp_reset_postdata();  // remember to reset the post data after your custom loop
    } else {
        // Display default related products
        woocommerce_output_related_products();
    }
}
add_action('woocommerce_after_single_product', 'my_custom_related_products_output', 55);


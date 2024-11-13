<?php

wp_enqueue_style( 'product_parent_page-styles', get_stylesheet_directory_uri().'/includes/css/woo-products-page-parent.css', array(), THEME_VERSION ); 

//arguments
$args = array(
    'posts_per_page'    => -1,
    'post_status'       => 'publish',
    'post_type'         => 'product',
    'meta_query'        => WC()->query->get_meta_query(),
    'post__in'          => array_merge( array( 0 ), wc_get_product_ids_on_sale() )
);

//get products on sale using wp_query class
$saleProducts = new WP_Query( $args ); ?>

<style>

.et-db #main.site-main #et-boc ul.products li.product {
    text-align: center;
    gap: 1em;
}	

.et-db #main.site-main #et-boc ul.products li.product img {
    height: 200px;
    object-fit: cover;
    margin-bottom: 1em;
}
	
	.et-db #main.site-main #et-boc ul.products li.product h2 {
		text-transform: uppercase;
		line-height: 1.2;
	}
	
	.et-db #main.site-main #et-boc ul.products li.product .subheading {
		font-size: 16px!important;
	}
	
	.et-db #main.site-main #et-boc ul.products li.product .price {
		font-size: 20px;
		margin-bottom: 1em;
	}
	
	.et-db #main.site-main #et-boc ul.products li.product .price del {
		font-size: 16px;
		color: rgb(var(--primary));
		font-weight: 500;
	}

	.et-db #main.site-main #et-boc ul.products li.product .price ins {
		font-weight: 700;
	}

</style>

<div class="products-container">

<?php if( $saleProducts->have_posts() ) : ?>
	
	<ul class="products">
		
		<?php while( $saleProducts->have_posts() ) : $saleProducts->the_post();

			global $product;

		?>
		
			<?php if( $product->is_type('simple') ) :

				$productLink = $product->get_permalink();
				$productTitle = get_the_title();
				$productPrice = $product->get_price_html();
				$image = wp_get_attachment_image( $product->get_image_id(), "large", true, array( "loading" => "lazy" ) );
				$categories = $product->get_categories();

			?>
		
			<li class="product">
		
				<div>
			
					<a href="<?= $productLink ?>">

						<?= $image ?>
						<p class="subheading"><?= $categories ?></p>
						<h2 class="woocommerce-loop-product__title"><?= $productTitle ?></h2>

					</a>
					
				</div>
			
				<div>
			
					<span class="price"><?= $productPrice ?></span>

					<a class="button wp-element-button product_type_variable add_to_cart_button" href="<?= $productLink ?>">Shop Product</a>
					
				</div>
				
			</li>

<?php elseif ($product->is_type('variable')) :

    $variants = $product->get_children();
    $lowest_price = false; // Variable to track the lowest price

    foreach ($variants as $variant) :
        $productVariation = new WC_Product_Variation($variant);
        $variationPrice = $productVariation->get_price();

        // Calculate and update the lowest price
        if (!$lowest_price || $variationPrice < $lowest_price) {
            $lowest_price = $variationPrice;
        }

    endforeach;

    // If at least one variation was found for the variable product, display the lowest price
    if ($lowest_price) :
        $formatted_lowest_price = wc_price($lowest_price);
        $productLink = $product->get_permalink(); // Use the main product link
        $variantTitle = $product->get_name();
        $categories = $product->get_categories();
        $image = wp_get_attachment_image($product->get_image_id(), "large", true, array("loading" => "lazy"));
        ?>
        <li class="product">
            <div>
                <a href="<?= $productLink ?>"> <!-- Use the main product link -->
                    <?= $image ?>
                    <p class="subheading"><?= $categories ?></p>
                    <h2 class="woocommerce-loop-product__title"><?= $variantTitle ?></h2>
                </a>
            </div>
            <div>
                <span class="price">Starting from <?= $formatted_lowest_price ?></span>
                <a class="button wp-element-button product_type_variable add_to_cart_button" href="<?= $productLink ?>">Shop Product</a>
            </div>
        </li>
    <?php
    // If no variation was found for the variable product, you can display a message
    else :
        echo '<p>No variations found for this product.</p>';
    endif;

endif;
?>

		<?php endwhile; wp_reset_query(); ?>

	</ul>
		
<?php else : ?>

    <p>Sorry, There are currently no products on sale.</p>

<?php endif; ?>
	
</div>
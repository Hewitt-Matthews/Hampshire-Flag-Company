<style>

	#product-search-results {
		background-color: #F8F9FA;
	}
	
	#product-search-results .grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(100%, 250px), 1fr));
		gap: 2em;
	}
	
	#product-search-results .grid .product {
		padding: 2em;
		background: #fff;
		text-align: center;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		align-items: center;
	}
	
	#product-search-results .grid .product img {
		margin-bottom: 1em;
	}
	
	#product-search-results .grid .product span {
		text-transform: uppercase;
		font-size: 12px;
		letter-spacing: 1px;
	}
	
	#product-search-results .grid .product h3 {
		font-size: 18px;
	}
	
	#product-search-results .grid .product .primary-btn {
		font-size: 14px;
	}

</style>

<?php 

$search_query = get_search_query();

$post_options = array(
	'post_type' => 'product',
	'posts_per_page' => -1,
	'post__not_in' => array(get_the_id()),
);

$query = new WP_Query( $post_options );

$terms = get_terms( 'product_cat', array(
    'name__like' => $search_query,
    'hide_empty' => false
) );

?>

<!-- <pre><?php print_r($terms) ?> </pre> -->

<h2>Products</h2>

<?php if ( $terms ) : ?>

	<div class="grid">

		<?php foreach ( $terms as $product_cat ) :
			$name = $product_cat->name;
			$slug = $product_cat->slug;

			$products = wc_get_products(array(
				'category' => $slug,
			));
			
		?>
		
			<?php if($products) :
					
				$product = $products[0]->get_data();
				$featuredImg = wp_get_attachment_image_src( get_post_thumbnail_id($product['id']), 'large' );
			?>		
<!-- 				<pre><?php print_r($product) ?></pre> -->
				<article class="product">

					<div class="img-container">
						<img src=" <?= $featuredImg[0] ?>"/>
					</div>

					<span><?= $categories[0]->name ?></span>
					<h3><?= $name ?></h3>
					<a href="/product-category/<?= $slug ?>" class="primary-btn blue">Shop Products</a>

				</article>

			<?php endif;?>

		<?php endforeach;
		wp_reset_postdata(); ?>

	</div>

<?php else : ?>

	<p>There are currently no products to show for the search term: "<?= $search_query ?>", please try a different search query.</p>

<?php endif; ?>
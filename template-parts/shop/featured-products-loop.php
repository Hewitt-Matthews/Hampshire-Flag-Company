<style>

	#featured-loop-container {
		background: #F8F9FA;
	}

	.featured-products-container .heading {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 2em;
	}
	
	.featured-products-container .heading h2 {
		margin: 0;
	}
	
	.featured-products-container .subheading {
		text-align: left;
	}
	
	.featured-products-container .category-list {
		display: flex;
		gap: 2em;
		flex-wrap: wrap;
		padding-bottom: 1em;
		margin-bottom: calc(1em + 2px);
		border-bottom: solid 2px rgb(var(--primary));
	}
	
	.featured-products-container .category-list p {
		margin: 0;
		text-transform: uppercase;
		font-size: 14px;
		letter-spacing: 1px;
		font-weight: 400;
		line-height: 1;
		position: relative;
	}
	
	.featured-products-container .category-list p:hover {
		cursor: pointer;
		font-weight: 800;
		transition: 200ms;
	}
	
	.featured-products-container .category-list p.active {
		color: rgb(var(--primary));
    	font-weight: 800;
	}
	
	.featured-products-container .category-list p.active::after {
		content: "";
		position: absolute;
		width: 100%;
		height: 4px;
		background-color: rgb(var(--primary));
		left: 0;
		bottom: calc(-1em - 5px);
	}

	.featured-products-container .featured-products-grid .category {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(min(100%, 250px), 1fr));
		gap: 1em;
		position: absolute;
		top: 0;
		z-index: -99;
		opacity: 0;
		transition: opacity 250ms;
	}
	
	.featured-products-container .featured-products-grid .category.active {
		position: relative;
		opacity: 1;
		z-index: 1;
	}

	.featured-products-container .featured-products-grid .category .product {
		background: #fff;
		text-align: center;
   		padding: 2em;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		align-items: center;
	}
	
	.featured-products-container .featured-products-grid .category .product img {
		margin: 0 auto 1em;
	}
	
	.featured-products-container .featured-products-grid .category .product h2 {
   		font-size: 20px;
		font-weight: 700;
		color: #000;
	}
	
	
	.featured-products-container .featured-products-grid .category p {
		text-transform: uppercase;
		font-weight: 600;
		letter-spacing: 1px;
		font-size: 14px;
	}
	
	.featured-products-container .featured-products-grid .category .product a[rel="tag"] {
		text-transform: uppercase;
		letter-spacing: 1px;
		font-weight: 600;
		font-size: 14px;
		color: #6d6d6d;
	}
	
	.featured-products-container {
		text-align: center;
	}
	
	a.all-products-btn {
		margin: auto;
		margin-top: 50px;
	}

	/* 404 Page Overrides */
	
	.error404 #page .featured-products-container .subheading {
		text-align: left!important;
		font-weight: 600;
		color: rgb(var(--primary))!important;
	}
	
	.error404 #page .featured-products-container .heading h2 {
   		text-transform: uppercase!important;
		margin-top: 1em!important;
	}

	.error404 #page .featured-products-container {
		text-align: center!important;
	}
	
	.error404 #page .featured-products-container .heading {
		margin-bottom: 2em!important;
	}
	
	.error404 #page .featured-products-container .category-list {
		padding-bottom: 1em!important;
		margin-bottom: calc(1em + 2px)!important;
		border-bottom: solid 2px rgb(var(--primary))!important;
	}
	
	.error404 #page .featured-products-container .featured-products-grid .category {
		transition: opacity 250ms!important;
	}
	
	.error404 #page .featured-products-container .featured-products-grid .category .product {
		background: #fff!important;
		text-align: center!important;
		padding: 2em!important;
	}
	
	.error404 #page .featured-products-container .featured-products-grid .category .product img {
		margin: 0 auto 1em!important;
	}
	
	.error404 #page .featured-products-container .featured-products-grid .category .product h2 {
		font-size: 20px!important;
		font-weight: 700!important;
		color: #000!important;
	}
	
	.error404 #page .primary-btn.blue {
		background-color: rgb(var(--secondary))!important;
	}
	
	.error404 #page .primary-btn {
		margin-top: 2em!important;
	}

</style>

<?php 

$product_categories = get_terms( array(
    'taxonomy' => 'product_cat',
) );

?>

<div class="featured-products-container">
	
	<p class="subheading">Featured Products</p>
	
	<div class="heading">
		
		<h2>Featured Products</h2>
		
	</div>
	
	<div class="featured-products">
		
		<?php
		
		$args = array(
			'featured' => true,
			'posts_per_page' => -1,
		);
		$featured_products = wc_get_products( $args );
		//print_r($featured_products);
		$featured_product_ids = wp_list_pluck( $featured_products, 'id' );
// 		$featured_product_titles = wp_list_pluck( $featured_products, 'name' );
// 		print_r($featured_product_titles);
		$featured_products_categories = [];
		
		foreach($featured_product_ids as $product_id) { 
							
			//Loop through all featured, and find it's highest ancestor cat, push into category array
			//This may need to be adjusted if they don't want to show the highest level cats only
			$main_product_cat = get_the_terms( $product_id, 'product_cat' )[0];
			$parent_cats = get_ancestors( $main_product_cat->term_taxonomy_id, 'product_cat');
			$highest_ancestor = $parent_cats[count($parent_cats) - 1];
			
			//If categoey doesn't exist in cat array, push it in as index with empty array, then push in the product to said index
			if (!array_key_exists($highest_ancestor, $featured_products_categories)) {
				$featured_products_categories[$highest_ancestor] = [];
			}
			
			array_push($featured_products_categories[$highest_ancestor], $product_id);
			

		};
		 
		 ?>
		
		<div class="category-list">
		
			<?php $count = 0; foreach($featured_products_categories as $product_cat => $v) :
				$cat_info = get_term_by('id', $product_cat, 'product_cat');
			?> 

					<p <?php if(!$count) : ?>class="active"<?php endif; ?>data-category="<?= $product_cat ?>"><?= $cat_info->name ?></p>

			<?php $count++; endforeach; ?>
		
		</div>	
		
		<div class="featured-products-grid">
			
			<?php $count = 0; foreach($featured_products_categories as $product_cat => $product) : ?> 

					<div class="category <?php if(!$count) : ?>active<?php endif; ?>" data-category="<?= $product_cat ?>">
						
						<?php $num = 1; foreach($product as $product_id) : 
						 	if ( $num <= 4 ) :
							$wc_product_object = wc_get_product( $product_id );
						 	$name = $wc_product_object->get_name();
						 	$permalink = get_permalink( $wc_product_object->get_id() );
						 	$image = $wc_product_object->get_image();
						 	$categories = $wc_product_object->get_categories();
						 	$first_category = '';
							 if ( ! empty( $categories ) ) {
								$categories_array = explode( ',', $categories );
								$first_category = trim( $categories_array[0] );
							}
						 	$price = $wc_product_object->get_price();
							$is_variable = $wc_product_object->is_type( 'variable' );
						 	$is_gravity = get_post_meta( $product_id, '_gravity_form_data' );
						?> 
						
							<div class="product">
<!-- 								<pre><?php print_r(get_post_meta( $product_id, '_gravity_form_data' ));?></pre> -->

								<?= $image ?>
								<?= $first_category ?>
								<h2><?= $name ?></h2>
								<?php if($is_variable || $is_gravity) : ?>
									<p class="starting-from">Starting from £<span class="product-price"><?= $price ?></span></p>
								<?php else : ?>
									<p>£<span class="product-price"><?= $price ?></span></p>
								<?php endif; ?>
								<a href="<?= $permalink ?>" class="primary-btn blue">View Product</a>
								
							</div>
						
						
						<?php endif; $num++; endforeach; ?>
						
					</div>

			<?php $count++; endforeach; ?>
			
			<a href="/shop" class="primary-btn all-products-btn">Explore All Products</a>
		
		</div>	
		
	</div>
		
</div>

<script>
	
	const initFeaturedProducts = () => {
		
		const categoryList = document.querySelector('.featured-products-container .category-list');
		const featuredProductsList = document.querySelector('.featured-products-container .featured-products-grid');
		
		const categoryListItems = [...categoryList.children];
		const featuredProductItems = [...featuredProductsList.children];
		
		const showProducts = (e) => {
			
			if (e.target.classList.contains('active')) return;
			
			const categoryID = e.target.dataset.category;
			
			categoryListItems.forEach(item => {
			
				item.classList.remove('active');

			})
			
			e.target.classList.add('active');
			
			featuredProductItems.forEach(item => {
				
				item.classList.remove('active');
				
				if(item.dataset.category == categoryID) {
					item.classList.add('active');
				}
				
			})
			
		}
		
		categoryListItems.forEach(item => {
			
			item.addEventListener('click', showProducts)
			
		})
			
	}
	
	window.addEventListener('load', initFeaturedProducts);


</script>

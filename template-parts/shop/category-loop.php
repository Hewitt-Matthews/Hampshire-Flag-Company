<?php

if(!wp_script_is('swiper')) {
	wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/includes/swiper/swiper-bundle.js', array(), THEME_VERSION, true );
	wp_enqueue_style( 'swiper_styles', get_stylesheet_directory_uri() . '/includes/swiper/swiper-bundle.css');
}
?>

<style>

	.product-cats-container .heading {
		display: flex;
		flex-wrap: wrap;
		gap: 2em;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 2em;
	}
	
	.product-cats-container .heading h2 {
		margin: 0;
	}
	
	.product-cats-container .heading > div {
		display: flex;
		gap: 2em;
	}
	
	.product-cats-container .heading.bottom {
		float: right;
   	 	padding-top: 30px;
	}

	.product-cats-container .heading > div .buttons {
		width: 135px;
		position: relative;
	}
	
	.product-cats-container .heading > div .buttons:has(.swiper-button-lock) {
		display: none;
	}

	.product-cats-container .heading > div > a {
		text-decoration: underline;
		text-transform: uppercase;
		letter-spacing: 2px;
		font-size: 14px;
		font-weight: 600;
		color: #000;
	}
	
	/* 	Fallback if Swiper doesn't initilise */
	.product-cats-container .product-categories.no-swiper .swiper-wrapper {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(250px, 100%), 1fr));
		gap: 2em;
	}
	
	.product-cats-container .product-categories .category {
		text-align: center;
	}
	
	.product-cats-container .product-categories .category img {
		margin-bottom: 1em;
		width: 100%;
		height: 300px;
		object-fit: cover;
	}
	
	.product-cats-container .product-categories .category h3 a {
		font-size: 20px;
		font-weight: 700;
		color: #000;
	}
	
	.product-cats-container .product-categories .category h3 a::after {
		content: "";
		position: absolute;
		inset: 0;
	}
	
	.product-cats-container .product-categories .category h3 a:hover {
		text-decoration: underline;
	}

	.product-cats-container .product-categories .category .starting-from {
		text-transform: uppercase;
		font-weight: 600;
		letter-spacing: 1px;
		font-size: 14px;
	}
	
	.product-cats-container .swiper-button-next,
	.product-cats-container .swiper-button-prev {
		--buttonSize: 50px;
   		background-color: rgb(var(--primary));
		color: #fff;
		border-radius: 50%;
		width: var(--buttonSize);
		height: var(--buttonSize);
	}
	
	.product-cats-container .swiper-button-next::after,
	.product-cats-container .swiper-button-prev::after {
		clip-path: polygon(0 40%, 70% 38%, 70% 20%, 100% 50%, 71% 80%, 70% 60%, 0 60%);
		width: 50%;
		height: 40%;
		background: #fff;
		display: block;
	}
	
	.swiper-button-prev:after {
		transform: rotateZ(180deg) translateY(2px);
	}
	
	@media only screen and (max-width: 768px) {
		.product-cats-container .heading.bottom {
			display: none;
		}
		
		#category-loop-container .hfc-row {
			padding: 4em 0 2em 0em;
		}
	}


</style>

<?php 

// $product_categories = get_terms( array(
//     'taxonomy' => 'product_cat',
// 	'childless' => true
// ) );

$categories = get_field('product_categories');
//$product_categories = array_slice($categories, 0, 8);
$product_categories = $categories;

if ( $product_categories ) : 
?>

<div class="product-cats-container">
	
	<p class="subheading">Product Categories</p>
	
	<div class="heading">
		
		<h2>What we offer</h2>
		
		<div>
			<a href="/shop">All Products</a>
			<div class="buttons">
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
			</div>
		</div>
	
		
	</div>
	
	<div class="product-categories swiper no-swiper">	
		
  			<div class="swiper-wrapper">
			
				<?php foreach( $product_categories as $cat ) :

					//Get Category Info
					$name = $cat->name;
					$description = $cat->description;
					$product_query = new WC_Product_Query( array( 'category' => $cat->slug ) );
					$products = $product_query->get_products();
					//Loop through all prodcuts in this category, push prices into array and sort in ascending order to find cheapest one
					$product_prices = wp_list_pluck( $products, 'price' );
					sort( $product_prices );
					$price_var = min( $product_prices );
					$image_id = get_term_meta($cat->term_id)['thumbnail_id'][0];
					if(!$image_id) {
						foreach ( $products as $product ) {
							$image_id = $product->get_image_id();
							break;
						}
					}

					$image = wp_get_attachment_image_src( $image_id, 'medium' )[0] ? wp_get_attachment_image_src( $image_id, 'medium' )[0] : '/wp-content/uploads/woocommerce-placeholder.png';


					if($products) :
				?>

					<div class="swiper-slide">
						<div class="category">

							<img src="<?= $image ?>" alt="Image for <?= $name ?>" loading="lazy">
							<h3><a href="/product-category/<?= $cat->slug ?>"><?= $name ?></a></h3>
							<?php if($description) : ?>
								<p><?= limit_text(strip_tags($description), 15) ?>...</p>
							<?php endif; ?>
							<p class="starting-from">Starting from £<?= $price_var ?></p>

						</div>
					</div>

				<?php endif; endforeach; ?>
			
			</div>
		
	</div>
	
	<div class="heading bottom">
		
		<div>
			<a href="/shop">All Products</a>
			<div class="buttons">
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
			</div>
		</div>
		
	</div>
	
</div>

<!-- Initialize Swiper -->
<script>
	
	window.addEventListener('load', () => {
		const categoryswiper = new Swiper(".product-cats-container .product-categories", {
			slidesPerView: 1,
			grid: {
				rows: 1,
				fill: "row",
			  },
			spaceBetween: 30,
			navigation: {
			  nextEl: ".product-cats-container .swiper-button-next",
			  prevEl: ".product-cats-container .swiper-button-prev",
			},
			breakpoints: {
				767: {
					grid: {
						rows: 2,
						fill: "row",
					},
					slidesPerView: 4
				}
			}
		});
		
		document.querySelector('.no-swiper').classList.remove('no-swiper');
		
	});
</script>

<?php endif; ?>
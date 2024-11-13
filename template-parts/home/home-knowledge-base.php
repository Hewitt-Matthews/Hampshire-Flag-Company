<?php

if(!wp_script_is('swiper')) {
	wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/includes/swiper/swiper-bundle.js', array(), THEME_VERSION, true );
	wp_enqueue_style( 'swiper_styles', get_stylesheet_directory_uri() . '/includes/swiper/swiper-bundle.css');
}

?>

<style>
	
	#knowledge-base-container {
		background-color: rgb(var(--secondary));
	}
	
	.knowledge-base .intro h2,
	.knowledge-base .intro p {
		color: #fff;
	}
	
	.knowledge-base .intro>div {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
		gap: 2em;
	}
	
	.knowledge_base_swiper {
		margin: 3em 0;
	}
	
	.knowledge_base_swiper .swiper-wrapper {
		padding-bottom: 2em;
	}
	
	.knowledge_base_swiper .swiper-slide {
		background-position: center;
		background-size: cover;
		background-repeat: no-repeat;
		padding: 2em;
	}
	
	.knowledge_base_swiper .swiper-slide::after {
		position: absolute;
		content: "";
		background: linear-gradient(0deg, rgb(0 0 0), rgb(0 0 0 / 0%));
		width: 100%;
		height: 50%;
		bottom: 0;
		left: 0;
		z-index: 1;
	}

	.knowledge_base_swiper .swiper-slide h3 {
		color: #fff;
		line-height: 1.2;
		margin-top: 8em;
		font-size: 20px;
		text-transform: none;
		position: relative;
		z-index: 2;
	}
	
	.knowledge_base_swiper .swiper-slide p {
		background: #fff;
		display: inline-block;
		border-radius: 50px;
		padding: 5px 1em;
		color: #000;
		text-transform: uppercase;
		font-weight: 600;
		letter-spacing: 1px;
		font-size: 13px;
	}

	.knowledge_base_swiper .progress {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 1em;
	}
	
	.knowledge_base_swiper .progress .swiper-scrollbar {
		--scrollHeight: 3px;
		height: var(--scrollHeight);
		background-color: #fff;
		border-radius: 0;
		position: relative;
		bottom: 0;
		width: 85%;
	}
	
	.knowledge_base_swiper .progress .swiper-scrollbar .swiper-scrollbar-drag {
		height: calc(var(--scrollHeight) * 2);
		background-color: rgb(var(--primary));
		top: -1.5px;
		border-radius: 0;
	}
	
	.knowledge_base_swiper .arrows {
		display: flex;
		gap: 1em;
		justify-content: space-between;
	}
	
	.knowledge_base_swiper .arrows>div {
		--arrowSize: 50px;
		background-image: url('<?= get_stylesheet_directory_uri() ?>/assets/hfc_arrow.svg');
		width: var(--arrowSize);
		height: var(--arrowSize);
		background-size: contain;
		transition: 300ms;
	}
	
	.knowledge_base_swiper .arrows>div:hover {
		cursor: pointer;
		opacity: 0.75;
	}
	
	.knowledge_base_swiper .hfc-swiper-next {
		transform: rotate(180deg);
	}
	
	@media only screen and (max-width: 768px) {
		.knowledge_base_swiper {
			margin: 3em 0 0em;
		}
	}

</style>

<?php 

$knowledge_heading = get_field('knowledge_heading');
$knowledge_desc = get_field('knowledge_description');

?>

<div class="knowledge-base">
	
	<div class="intro">
		
		<p class="subheading white">Knowledge Base</p>
		
		<div>
			
			<h2><?= $knowledge_heading ?></h2>
			
			<div>
				
				<p><?= $knowledge_desc ?></p>
				<a href="/knowledge-base" class="primary-btn">Knowledge Base</a>
				
			</div>
			
		</div>
		
	</div>
	
	<?php
	
		$query = new WP_Query( array(
			'post_type' => 'post',
			'posts_per_page' => 10,
		) );

		if ( $query->have_posts() ) : ?>	

			<div class="swiper knowledge_base_swiper">

				<div class="swiper-wrapper">

					<?php while ( $query->have_posts() ) : 
						$query->the_post();
						$featuredImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' ); 

					?>

						<a href="<?= get_the_permalink(); ?> " class="swiper-slide" style="background-image: url(<?= $featuredImg[0] ?>)">

							<p>Category</p>
							<h3><?= get_the_title(); ?></h3>						

						</a>

					<?php endwhile;
					wp_reset_postdata(); ?>

				</div>
				
				<div class="progress">
					
					<div class="swiper-scrollbar"></div>
					
					<div class="arrows">
					
						<div class="hfc-swiper-prev"></div>
						<div class="hfc-swiper-next"></div>
						
					</div>
					
				</div>

			</div>

		<?php endif; 
	?>
	
	<script>
		
		window.addEventListener('load', () => {
			
			const knowledgeBaseSwiper = new Swiper(".knowledge_base_swiper", {
				slidesPerView: 1,
				spaceBetween: 30,
				navigation: {
				  nextEl: ".knowledge_base_swiper .hfc-swiper-next",
				  prevEl: ".knowledge_base_swiper .hfc-swiper-prev",
				},
				scrollbar: {
				  el: ".knowledge_base_swiper .swiper-scrollbar",
				},
				breakpoints: {
				  640: {
					slidesPerView: 1,
					spaceBetween: 20,
				  },
				  768: {
					slidesPerView: 2,
					spaceBetween: 40,
				  },
				  1024: {
					slidesPerView: 3,
					spaceBetween: 50,
				  },
				},
			});
		
		});
	</script>
	
	
</div>
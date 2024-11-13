<?php

if(!wp_script_is('swiper')) {
	wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/includes/swiper/swiper-bundle.js', array(), THEME_VERSION, true );
	wp_enqueue_style( 'swiper_styles', get_stylesheet_directory_uri() . '/includes/swiper/swiper-bundle.css');
}

?>

<style>

	#page .work_swiper.swiper {
		background-color: rgb(var(--secondary));
	}
	
	#page #et-boc .et-l div {
		transition: inherit;
	}
	
	@media only screen and (min-width: 980px) {
		#page .work_swiper.swiper {
			margin: 5em;
			transform: translateX(-30%);
		}
	}
	
	#page .work_swiper.swiper * {
		color: #fff;
	}
	
	#page .work_swiper.swiper .swiper-slide {
		padding: 5em 5em 0; 
		text-align: center;
		transition: inherit;
	}
	
	#page .work_swiper .buttons {
		position: relative;
		height: 100px;
		width: 160px;
		margin: 0 auto 5em;
	}
	
	#page .work_swiper .swiper-button-next,
	#page .work_swiper .swiper-button-prev {
		--buttonSize: 50px;
   		background-color: rgb(var(--primary));
		color: #fff;
		border-radius: 50%;
		width: var(--buttonSize);
		height: var(--buttonSize);
	}
	
	#page .work_swiper .swiper-button-next::after,
	#page .work_swiper .swiper-button-prev::after {
		content: '\24';
    	font-family: 'ETmodules';
		font-size: calc(var(--buttonSize) / 2);
	}
	
	.swiper-button-prev:after {
		transform: rotateZ(180deg) translateY(2px);
	}

</style>

<?php

$work = get_field('work');

if ( $work ) : ?>

<div class="swiper work_swiper">
	
	<div class="swiper-wrapper">

			<?php foreach ($work as $item) : ?>

				<div class="swiper-slide">

					<h3><?= $item['work_title'] ?></h3>
					<p><?= $item['work_description'] ?></p>

				</div>

			<?php endforeach; ?>

	</div>
	
	<div class="buttons">

		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>

	</div>	
	
</div>


<!-- Initialize Swiper -->
<script>
	window.addEventListener('load', () => {
		
		const workSwiper = new Swiper(".work_swiper", {
			slidesPerView: 1,
			observer: true,
			observeParents: true,
			navigation: {
			  nextEl: ".work_swiper .swiper-button-next",
			  prevEl: ".work_swiper .swiper-button-prev",
			},
		});
		
	})

</script>

<?php endif; ?>
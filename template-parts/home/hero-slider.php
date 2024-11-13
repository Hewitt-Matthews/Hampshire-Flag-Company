<?php

if(!wp_script_is('swiper')) {
	wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/includes/swiper/swiper-bundle.js', array(), THEME_VERSION, true );
	wp_enqueue_style( 'swiper_styles', get_stylesheet_directory_uri() . '/includes/swiper/swiper-bundle.css');
}

?>

<style>
	
	.home_swiper.swiper {
		height: 100%;
	}
	
	.home_swiper .swiper-container {
	  max-height: 100%;
	} 
	
	.home_swiper .swiper-slide {
		position: relative;
		width: 100%!important;
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
	}
	
	.home_swiper .swiper-slide .video-container {
		position: unset;
	}
	
	.home_swiper .swiper-slide .video-container img {
		object-fit: cover;
	}
	
	.home_swiper .swiper-slide .video-container .et_pb_video_play {
		left: 45%;
		top: 45%;
	}
	
	.home_swiper .swiper-slide a {
		position: absolute;
		inset: 0;
	}
	
	.home_swiper .video-slide {
		background: #000;
		display: grid;
    	align-items: center;
	}
	
	.home_swiper .swiper-slide .video-container .quality-selector {
		position: relative;
		bottom: 75px;
		left: 130px;
		background-color: rgba(0, 0, 0, 0.5);
		color: #fff!important;
		padding: 5px;
		border-radius: 5px;
	}
		
	.home_swiper .swiper-button-next,
	.home_swiper .swiper-button-prev {
		--buttonSize: 50px;
   		background-color: rgb(var(--primary));
		color: #fff;
		border-radius: 50%;
		width: var(--buttonSize);
		height: var(--buttonSize);
	}
	
	.home_swiper .swiper-button-next::after,
	.home_swiper .swiper-button-prev::after {
		content: '';
		clip-path: polygon(0 40%, 70% 38%, 70% 20%, 100% 50%, 71% 80%, 70% 60%, 0 60%);
		width: 50%;
		height: 40%;
		background: #fff;
		display: block;
	}
	
	.swiper-button-prev:after {
		transform: rotateZ(180deg) translateY(2px);
	}


</style>

<?php

$home_slider = get_field('home_slider');


?>

<div class="swiper home_swiper">
	
	<div class="swiper-wrapper">
	
		
		<?php foreach ($home_slider as $file) :
		
				$image_or_video = $file['image_or_video'];
		
				if ($image_or_video == 'image') : 
		
				$image = $file['image']; ?>
		
				<div class="swiper-slide" style="background-image: url(<?= $image['sizes']['large'] ?>)">
					<?php if($image['caption']) : ?><a href="<?= $image['caption'] ?>"></a><?php endif; ?>
				</div>	
		
				<?php elseif ($image_or_video == 'video') : 
		
				$video_high = $file['video_high'];
				$video_low = $file['video_low'];
				$thumbnail = $file['video_thumbnail'];
				?>

				<div class="swiper-slide video-slide">
					<?php echo do_shortcode("[video high_quality='{$video_high}' medium_quality='{$video_low}' thumbnail='{$thumbnail}']"); ?>
				</div>
		
		<?php endif; endforeach; ?>
			


	</div>
	
	<div class="swiper-button-next"></div>
	<div class="swiper-button-prev"></div>
	
</div>

<!-- Initialize Swiper -->
<script>
	
	window.addEventListener('load', () => {
		
			const swiper = new Swiper(".home_swiper", {
				slidesPerView: 1,
				navigation: {
				  nextEl: ".home_swiper .swiper-button-next",
				  prevEl: ".home_swiper .swiper-button-prev",
				},
			});
		
	})	

</script>
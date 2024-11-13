<style>

	#page #primary .services-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(100%, 410px), 1fr));
		gap: 3em 1em;
	}
	
	#page #primary .services-grid .service .service-img {
		width: 100%;
		height: min(80vw, 500px);
		object-fit: cover;
	}
	
	#page #primary .services-grid .service .service-icon {
		max-width: 100%;
		max-height: 50px;
		margin-right: 10px;
	}
	
	#page #primary .services-grid .service h2 {
		text-transform: uppercase;
		letter-spacing: 2px;
		margin: 30px 0px 20px 0px;
		font-weight: 700;
		display: flex;
		justify-content: left;
		align-items: center;
	}
	
	#page #primary .services-grid .service ul {
		margin: 1em 0 2em;
		max-width: 400px;
	}

	#page #primary .services-grid .service p {
		max-width: 600px;
    	font-size: 16px;
    	font-weight: 400;
	}

	
	#page #primary .services-grid .service ul li {
		border-bottom: solid 1px;
		text-transform: uppercase;
		letter-spacing: 1px;
		list-style: none;
		font-size: 18px;
		padding: 1em 0;
	}

</style>

<?php

$post_options = array(
	'post_type' => 'services',
	'posts_per_page' => -1,
);

$query = new WP_Query( $post_options );

?>
	
<?php if ( $query->have_posts() ) : ?>

	<div class="services-grid">

		<?php while ( $query->have_posts() ) : $query->the_post();
			$featuredImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );	
			$service_title = get_the_title();
			$service_desc = get_field('description');
			$listed_services = get_field('service_list');
			$service_icon = get_field('service_icon');
			$service_link = get_the_permalink();
		?>

			<div class="service">

				<img class="service-img" src=" <?= $featuredImg[0] ?>"/>

				<h2><img class="service-icon" src=" <?= $service_icon ?>"/> <?= $service_title ?></h2>
				<p><?= $service_desc ?></p>
				
				<ul>

					<?php $i = 0; foreach($listed_services as $service) : 
		
						if ( $i > 3 ) :
					
							break;
						
						else:
					?>
						
						<li><?= $service['listed_service'] ?></li>
					
					<?php endif; $i++; endforeach; ?>
					
				</ul>
				
				<a href="/contact-us/ " class="primary-btn">Contact Us</a>
				
			</div>

		<?php endwhile;
		wp_reset_postdata(); ?>

	</div>

<?php else : ?>

	<p>There are currently no services to show, please check back later.</p>

<?php endif; ?>
	
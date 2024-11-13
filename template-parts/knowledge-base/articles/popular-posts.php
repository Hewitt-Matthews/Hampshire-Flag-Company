<style>
	
	.popular-articles .grid article a {
    		color: rgb(var(--primary))!important;
		text-transform: uppercase!important;
		letter-spacing: 2px!important;
		font-size: 14px!important;
		font-weight: 600!important;
	}

	.popular-articles .grid article p {
		margin-bottom: 20px!important;
	}
	
	.popular-articles .grid article h3 {
		line-height: 1.2!important;
		font-size: min(20px, 8vw)!important;
		margin: 1em 0!important;
		text-transform: uppercase!important;
    		font-weight: 600!important;
    		letter-spacing: 1px!important;
	}

	.popular-articles .grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
		gap: 2em;
		text-align: left;
	}
	
	.popular-articles .grid article img {
		width: 100%!important;
		height: 250px!important;
		object-fit: cover!important;
	}
	
	.popular-articles .grid article .img-container {
   		position: relative;
	}

	.popular-articles .grid article .img-container span {
		position: absolute;
		top: 5%;
		right: 5%;
		background: #fff!important;
		padding: 5px 15px!important;
		border-radius: 50px!important;
		text-transform: uppercase;
		font-size: 13px;
		letter-spacing: 1px;
		font-weight: 600;
	}
	
	.popular-articles .primary-btn {
		margin-top: 2em;
	}

</style>

<?php 

$popularPostsQuery = new WP_Query( 
	array (
		'post_type' => 'post',
		'posts_per_page' => 6,
		'post__not_in' => array(get_the_id()),
		'order' => 'RAND' 
	)
);

?>


<div class="popular-articles">
	
	<?php if ( $popularPostsQuery->have_posts() ) : ?>
	
		<div class="grid">
			
			<?php while ( $popularPostsQuery->have_posts() ) : $popularPostsQuery->the_post();
			
				$featuredImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );	
				$categories = get_the_category();

			?>

				<article class="post">
					
					<div class="img-container">
						<img src=" <?= $featuredImg[0] ?>"/>
						<span><?= $categories[0]->name ?></span>
					</div>
					
					<h3><?= get_the_title(); ?></h3>
					<p><?= limit_text(wp_filter_nohtml_kses(apply_filters( 'the_content', get_the_content() )), 25); ?>...</p>
					<a href="<?= get_the_permalink(); ?> ">Read More &#8594;</a>
					
				</article>
			  	
			<?php endwhile;
			wp_reset_postdata(); ?>

		</div>
	
	<?php else : ?>
	
		<p>There are currently no recent articles to show, please check back later.</p>
	
	<?php endif; ?>
	
</div>
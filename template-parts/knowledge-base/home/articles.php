<style>
	
	.recent-articles {
		text-align: center;
	}
	
	.recent-articles .heading {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 2em;
	}

	.recent-articles .heading h2 {
		margin: 0;
	}
	
	.recent-articles .heading a,
	.recent-articles .grid article a {
    	color: rgb(var(--primary));
		text-transform: uppercase;
		letter-spacing: 2px;
		font-size: 14px;
		font-weight: 600;
	}
	
	.recent-articles .grid article h3 {
		line-height: 1.2;
		font-size: min(20px, 8vw);
		margin: 1em 0;
	}

	.recent-articles .grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
		gap: 2em;
		text-align: left;
	}
	
	.recent-articles .grid article img {
		width: 100%;
		height: 250px;
		object-fit: cover;
	}
	
	.recent-articles .grid article .img-container {
   		position: relative;
	}

	.recent-articles .grid article .img-container span {
		position: absolute;
		top: 5%;
		right: 5%;
		background: #fff;
		padding: 5px 15px;
		border-radius: 50px;
		text-transform: uppercase;
		font-size: 13px;
		letter-spacing: 1px;
		font-weight: 600;
	}
	
	.recent-articles .primary-btn {
		margin-top: 2em;
	}
	
	@media only screen and (max-width: 768px) {
		.knowledge_base_swiper {
			margin: 3em 0 2em;
		}
	}
	

</style>

<?php

$post_options = array(
	'post_type' => 'post',
	'posts_per_page' => 3,
	'post__not_in' => array(get_the_id()),
);

$query = new WP_Query( $post_options );

?>

<div class="recent-articles">
	
	<div class="heading">
		
		<h2>Recent Articles</h2>
		<a href="/knowledge-base/articles">View all articles &#8594;</a>
		
	</div>
	
	<?php if ( $query->have_posts() ) : ?>
	
		<div class="grid">
			
			<?php while ( $query->have_posts() ) : $query->the_post();
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
	
		<a class="primary-btn" href="/knowledge-base/articles">More Articles</a>
	
	<?php else : ?>
	
		<p>There are currently no recent articles to show, please check back later.</p>
	
	<?php endif; ?>
	
</div>
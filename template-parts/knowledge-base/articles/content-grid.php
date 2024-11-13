<style>
	
	#page #primary .recent-articles .grid article a, #page #content .recent-articles .grid article a {
    	color: rgb(var(--primary));
		text-transform: uppercase;
		letter-spacing: 2px;
		font-size: 14px;
		font-weight: 600;
	}
	
	#page #primary .recent-articles .grid article h3, #page #content .recent-articles .grid article h3 {
		line-height: 1.2;
		font-size: min(20px, 8vw);
		margin: 1em 0;
		text-transform: uppercase;
   		font-weight: 600;
	}

	#page #primary .recent-articles .grid, #page #content .recent-articles .grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
		gap: 2em;
		text-align: left;
	}
	
	#page #primary .recent-articles .grid article img, #page #content .recent-articles .grid article img {
		width: 100%;
		height: 250px;
		object-fit: cover;
	}
	
	#page #primary .recent-articles .grid article .img-container, #page #content .recent-articles .grid article .img-container {
   		position: relative;
	}

	#page #primary .recent-articles .grid article .img-container span, #page #content .recent-articles .grid article .img-container span {
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
	
	#page #primary .recent-articles .primary-btn, #page #content .recent-articles .primary-btn {
		margin-top: 2em;
	}

</style>

<?php

$category = get_category( get_query_var( 'cat' ) );
$catTitle = $category->name;

$post_options = array(
	'post_type' => 'post',
	'posts_per_page' => -1,
// 	'post__not_in' => array(get_the_id()),
	'category_name' => $catTitle
);

$query = new WP_Query( $post_options );

?>

<div class="recent-articles">
	
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
	
	<?php else : ?>
	
		<p>There are currently no recent articles to show, please check back later.</p>
	
	<?php endif; ?>
	
</div>
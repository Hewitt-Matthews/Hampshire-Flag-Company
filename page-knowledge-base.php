<?php
/**
 * The template for displaying the Knowledge Base page.
 *
 * Template name: Knowledge Base Template
 *
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<!--  Hero Section	 -->
					<?php get_template_part('template-parts/knowledge-base/home/hero') ?>
					
					<!--  FAQs Section	 -->
					<?php hfc_section('faqs-container', 'template-parts/knowledge-base/home/faqs' ); ?>
					
					<!--  Articles Section	 -->
					<?php hfc_section('articles-container', 'template-parts/knowledge-base/home/articles' ); ?>
					
					<!--  Search Section	 -->
					<?php hfc_section('search-container', 'template-parts/knowledge-base/home/search' ); ?>
					
					
					<!--  Need More Help Section	 -->
					<?php hfc_section('need-help', 'template-parts/knowledge-base/home/more-help' ); ?>


				</div><!-- #post-## -->
			
			</main><!-- #main -->
		</div><!-- #primary -->

<?php
get_footer();

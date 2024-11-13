<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @package storefront
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php
				$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
				?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="<?php storefront_homepage_content_styles(); ?>" data-featured-image="<?php echo esc_url( $featured_image ); ?>">

					<!--  Hero Section	 -->
					<?php get_template_part('template-parts/home/hero') ?>
					
					<!--  Feefo Small Section	 -->
					<?php hfc_section('feefo-small-widget', 'template-parts/global-feefo-simple' ); ?>
					
					<!--  Category Section	 -->
					<?php hfc_section('category-loop-container', 'template-parts/shop/category-loop' ); ?>
					
					<!--  Featured Products Section	 -->
					<?php hfc_section('featured-loop-container', 'template-parts/shop/featured-products-loop' ); ?>
					
					<!--  Knowledge Base Section	 -->
					<?php hfc_section('knowledge-base-container', 'template-parts/home/home-knowledge-base' ); ?>
					
					<!--  Feefo Section	 -->
					<?php hfc_section('feefo-widget', 'template-parts/global-feefo-large' ); ?>

					<!-- Company Achievements Section -->
					<?php hfc_section('company-achievements', 'template-parts/home/company-achievements'); ?>
					
					<!--  Client Logos Section	 -->
					<?php hfc_section('client-logos', 'template-parts/global-client-logos' ); ?>
					
					<!-- Newsletter CTA with Map -->
					<?php hfc_section('newsletter-map-cta', 'template-parts/global-newsletter-cta-with-map' ); ?>

				</div><!-- #post-## -->
			
			</main><!-- #main -->
		</div><!-- #primary -->

<?php
get_footer();

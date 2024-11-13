<?php
/**
 * The template for displaying search results pages.
 *
 * @package storefront
 */

get_header(); ?>

<style>

	.search-results .storefront-breadcrumb {
		display: none;
	}
	
	header.page-header {
		background-color: rgb(var(--secondary));
		text-align: center;
		padding: 5em 0;
	}
	
	header.page-header h1 {
		color: #fff;
		font-size: 14px;
    	font-weight: 400;
	}
	
	header.page-header h1 span {
		display: block;
		font-size: 50px;
		font-weight: 700;
	}

</style>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						/* translators: %s: search term */
						printf( esc_attr__( 'Search Results for %s', 'storefront' ), '<span>"' . get_search_query() . '"</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<?php 
			
				if ( isset( $_GET['search_type'] ) ) {
					hfc_section("post-search-results",  'template-parts/knowledge-base/search/results-articles');
				} else {
					hfc_section("product-search-results",  'template-parts/knowledge-base/search/results-products');
				}

		else :

			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();

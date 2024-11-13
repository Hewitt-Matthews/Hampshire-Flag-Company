<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W6TNT68');</script>
<!-- End Google Tag Manager -->
	
<meta name="google-site-verification" content="8ZDQw77KtUXRNM6gkoGKdz-i89nR1ed1zh83TFzw1jY" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W6TNT68"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	
	<div id="loading-screen"></div>
	
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">
		
		<div class="desktop-nav">			
			
			<?php
			/**
			 * Functions hooked into storefront_header action
			 *
			 * @hooked storefront_header_container                 - 0
			 * @hooked storefront_skip_links                       - 5
			 * @hooked storefront_social_icons                     - 10
			 * @hooked storefront_site_branding                    - 20
			 * @hooked storefront_product_search                   - 35
			 * @hooked hm_header_tel		                       - 38
			 * @hooked hm_header_account                  		   - 39
			 * @hooked storefront_header_cart                      - 40
			 * @hooked storefront_header_container_close           - 41
			 * @hooked storefront_primary_navigation_wrapper       - 42
			 * @hooked storefront_primary_navigation               - 50
			 * @hooked storefront_primary_navigation_wrapper_close - 68
			 */
			do_action( 'storefront_header' );
			?>
			
			<?php if (is_user_logged_in() && current_user_can('wcwp_wholesale')) : 
					$current_user = wp_get_current_user();
				?>
				 <div class="wholesale-notice">Hi <?= $current_user->display_name; ?>, you're logged in to a wholesale account.</div>
				<?php endif; ?>
			
			<?php
				/**
				 * Newsletter CTA
				 */
				
				$newsletter_cta = get_field('newsletter_cta', 'option');
				$show_newsletter_cta = $newsletter_cta['show_newsletter_cta'];
				$newsletter_cta_text = $newsletter_cta['newsletter_cta_text'];
				$newsletter_colour = $newsletter_cta['newsletter_colour'];
			
			if ( $show_newsletter_cta ) : ?>

				<div class="newsletter-cta"<?php if($newsletter_colour) : ?> style="background: <?= $newsletter_colour ?>"<?php endif; ?>>
					<?= $newsletter_cta_text ?>
<!-- 					<p>Subscribe to our e-update us and receive 10% off your next order! <a href="/#newsletter-map-cta">Subscribe here</a></p> -->
				</div>
			
			<?php endif; ?>
			
		</div>
		
		<div class="mobile-nav">
			
			<div class="mobile-nav-main">
				
				<button onclick="openSearch()">
				
					<svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
						<path d="M 16.722523,17.901412 C 16.572585,17.825208 15.36088,16.670476 14.029846,15.33534 L 11.609782,12.907819 11.01926,13.29667 C 8.7613237,14.783493 5.6172703,14.768302 3.332423,13.259528 -0.07366363,11.010358 -1.0146502,6.5989684 1.1898146,3.2148776
						  1.5505179,2.6611594 2.4056498,1.7447266 2.9644271,1.3130497 3.4423015,0.94387379 4.3921825,0.48568469 5.1732652,0.2475835 5.886299,0.03022609 6.1341883,0 7.2037391,0 8.2732897,0 8.521179,0.03022609 9.234213,0.2475835 c 0.781083,0.23810119 1.730962,0.69629029 2.208837,1.0654662
						  0.532501,0.4113763 1.39922,1.3400096 1.760153,1.8858877 1.520655,2.2998531 1.599025,5.3023778 0.199549,7.6451086 -0.208076,0.348322 -0.393306,0.668209 -0.411622,0.710863 -0.01831,0.04265 1.065556,1.18264 2.408603,2.533307 1.343046,1.350666 2.486621,2.574792 2.541278,2.720279 0.282475,0.7519
						  -0.503089,1.456506 -1.218488,1.092917 z M 8.4027892,12.475062 C 9.434946,12.25579 10.131043,11.855461 10.99416,10.984753 11.554519,10.419467 11.842507,10.042366 12.062078,9.5863882 12.794223,8.0659672 12.793657,6.2652398 12.060578,4.756293 11.680383,3.9737304 10.453587,2.7178427
						  9.730569,2.3710306 8.6921295,1.8729196 8.3992147,1.807606 7.2037567,1.807606 6.0082984,1.807606 5.7153841,1.87292 4.6769446,2.3710306 3.9539263,2.7178427 2.7271301,3.9737304 2.3469352,4.756293 1.6138384,6.2652398 1.6132726,8.0659672 2.3454252,9.5863882 c 0.4167354,0.8654208 1.5978784,2.0575608
						  2.4443766,2.4671358 1.0971012,0.530827 2.3890403,0.681561 3.6130134,0.421538 z"></path>
					</svg>
				
				</button>
				
				<script>
				
					const openSearch = () => {
						
						const search = document.querySelector('header .mobile-nav-search > .site-search');
						
						search.classList.toggle('open');
						
					}
				
				</script>
				
				<?php storefront_site_branding() ?>
			
				<div class="nav-buttons">
					
					<button  onclick="openNav(this)" class="nav-toggle">
						<div class="line one"></div>
						<div class="line two"></div>
						<div class="line three"></div>
					</button>
					
					<script>

						const openNav = (button) => {

							button.classList.toggle('open');
							const header = document.querySelector('header');
							header.classList.toggle('open');
							window.scrollTo({
							  top: 0,
							  left: 0,
							  behavior: "smooth",
							});

						}

					</script>
					
					<?php storefront_header_cart() ?>
					
				</div>
				
			</div>
			
			<div class="mobile-nav-search">
				<?php storefront_product_search() ?>
			</div>
			
			<div class="mobile-nav-menu">
				<?php wp_nav_menu( array( 'menu' => 'Primary Menu' ) ); ?>
			</div>
		
		</div>

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */

	if(!is_page()) {
		do_action( 'storefront_before_content' );
	}
	?>

	<div id="content" class="site-content" tabindex="-1">
<!-- 		<div class="col-full"> -->
		<?php

		if(!is_page()) {
			do_action( 'storefront_content_top' );
		}	
		
		?>

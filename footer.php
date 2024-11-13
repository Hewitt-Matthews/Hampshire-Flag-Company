<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

$hfc_address = get_field('hfc_address', 'option');
$hfc_phone = get_field('hfc_phone_number', 'option');
$hfc_email = get_field('hfc_email_address', 'option');
$footer_menus = wp_get_nav_menus();
$sales_and_support = get_field('sales_and_support_availability', 'option');
$social_accounts = get_field('social_accounts', 'option');
$payment_methods = get_field('accepted_payment_methods', 'option');
$accreditation_logos = get_field('accreditation_logos', 'option');
$hfc_footer_text = get_field('hfc_footer_text', 'option');

?>
<!-- 		</div>.col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
			
		<div class="footer-top">

			<script type="text/javascript" src=https://api.feefo.com/api/javascript/hampshire-flag async></script>
			<div id="feefo-service-review-floating-widgetId"></div>
			<div class="hfc-row">

				<div class="newsletter-cta">

					<div class="logo">

						<img src="/wp-content/uploads/2023/04/HFC-White-logo.png" width="auto" height="auto" style="max-height:105px;" alt="HFC Logo in white">
						<div class="line"></div>
						
					</div>
					
					<div class="subscribe-footer">
						
						<p>Sign up to our e-updates</p>
						<?php echo do_shortcode('[gravityform id="4" title="false"]') ?>
						
					</div>

				</div>

				<div class="menus-container">
					
					<div class="address-and-contact">
						
						<h2>Contact Us</h2>
						
						<p><?= $hfc_address ?></p>
						
						<div>
							<p>T: <?= $hfc_phone ?></p>
							<p>E: <?= $hfc_email ?></p>
						</div>
						
					</div>
					
					<?php foreach($footer_menus as $menu) :
						$menu_title = trim(str_replace("Footer Menu - ", "", $menu->name));
					?>
					
						<?php if (strpos($menu->name, 'Footer Menu - ') !== false) : ?>
					
							<div class="footer-menu">

								<h2><?= $menu_title ?></h2>

								<?php wp_nav_menu( array( 'menu' => $menu->term_id ) );?>

							</div>
					
						<?php endif; ?>

					<?php endforeach; ?>

				</div>

				<div class="accreditations-and-payments">

					<div>

						<div class="support-opening">
							
							<p>
								Sales and Support Lines available:<br>
								<?= $sales_and_support ?>
							</p>

						</div>

						<div class="payment-methods">
							
							<?php foreach($payment_methods as $method) : ?>
							
								<?php if ($method == "visa") : ?>
									<img src="/wp-content/uploads/2023/04/Visa.png" alt="Icon for the <?= $method ?> payment method">
								<?php elseif ($method == "mastercard") : ?>
									<img src="/wp-content/uploads/2023/04/Mastercard.png" alt="Icon for the <?= $method ?> payment method">
								<?php elseif ($method == "apple-pay") : ?>
									<img src="<?= get_stylesheet_directory_uri() . '/assets/'. $method . '-icon.svg' ?>" alt="Icon for the <?= $method ?> payment method">
								<?php else : ?>
									<img src="/wp-content/uploads/2023/04/PayPal.png" alt="Icon for the <?= $method ?> payment method">
								<?php endif;?>
							
							<?php endforeach; ?>

						</div>

					</div>

					<div class="accreditations">
						
						<?php foreach($accreditation_logos as $logo) : ?>
						
							<?= wp_get_attachment_image( $logo, "large", true, array( "loading" => "lazy" ) ); ?>
						
						<?php endforeach; ?>

					</div>

				</div>

			</div>
			
			<div class="footer-bottom legal">
				
				<div class="hfc-row">
					
					<div>
						
						<div class="legal-info">
							<p><?= $hfc_footer_text ?></p>
						</div>
						
						<div class="social-accounts">
							
							<?php foreach($social_accounts as $social) : ?>
							
								<?php if ($social['social_account'] == "twitter") : ?>
									<a href="<?= $social['link'] ?>" target="_blank"><img src="<?= get_stylesheet_directory_uri() . '/assets/'. $social['social_account'] . '-icon.svg' ?>" alt="Icon for the <?= $social ?> social account"></a>
								<?php elseif ($social['social_account'] == "facebook") : ?>
									<a href="<?= $social['link'] ?>" target="_blank"><img src="<?= get_stylesheet_directory_uri() . '/assets/'. $social['social_account'] . '-icon.svg' ?>" alt="Icon for the <?= $social ?> social account"></a>
								<?php elseif ($social['social_account'] == "linkedin") : ?>
									<a href="<?= $social['link'] ?>" target="_blank"><img src="<?= get_stylesheet_directory_uri() . '/assets/'. $social['social_account'] . '-icon.svg' ?>" alt="Icon for the <?= $social ?> social account"></a>
								<?php elseif ($social['social_account'] == "youtube")  : ?>
									<a href="<?= $social['link'] ?>" target="_blank"><img src="<?= get_stylesheet_directory_uri() . '/assets/'. $social['social_account'] . '-icon.svg' ?>" alt="Icon for the <?= $social ?> social account"></a>
								<?php else : ?>
									<a href="<?= $social['link'] ?>" target="_blank"><img src="<?= get_stylesheet_directory_uri() . '/assets/'. $social['social_account'] . '-icon.svg' ?>" alt="Icon for the <?= $social ?> social account"></a>
								<?php endif;?>
							
							<?php endforeach; ?>
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
		
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

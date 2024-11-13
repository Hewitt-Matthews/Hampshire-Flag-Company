<style>
	
	#client-logos h2 {
		text-align: center!important;
	}
	
	#client-logos h2.other-header {
		padding-bottom: 25px;
	}
	
	#client-logos .logos-container img {
		max-width: 150px;
		margin: 0 auto;
	}

	#client-logos .logos-container {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
		align-items: center;
		gap: 2em;
		margin-top: 3em;
	}
	
	@media only screen and (max-width: 980px) {
		
		#client-logos .logos-container {
			grid-template-columns: auto;
			grid-auto-flow: column;
			overflow: auto;
			scroll-snap-type: x mandatory;
			align-items: center;
   			padding-bottom: 1em;
		}
		
		#client-logos .logos-container .logo {
			width: 100px;
			scroll-snap-align: start;
		}
		
	}
	
</style>

<?php 

$charity_logos = get_field('charity_logos', 'option');

?>


<?php
if ( is_home() ) {
    ?> <h2>Who we’ve worked with</h2> 
<?php } else {
    ?> <h2 class="other-header">Some of the fantastic charities we work with:</h2> 
<?php }
?>


	<div class="logos-container">

		<?php foreach($charity_logos as $logo) : ?>
			<div class="logo">
				<?= wp_get_attachment_image( $logo, "large", true, array( "loading" => "lazy" ) ); ?>
			</div>
		<?php endforeach; ?>

	</div>
	




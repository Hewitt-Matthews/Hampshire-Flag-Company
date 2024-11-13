<style>
	
	#newsletter-map-cta > .hfc-row {
    	width: 100%;
		max-width: 100%;
		padding: 0;
	}

	.cta-container {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
	}
	
	.cta-container>.newsletter-cta {
		padding: min(7.5em, 15vw);
/* 		padding-left: calc(calc(100vw - min(1250px, 80vw)) / 2); */
		background-color: #002A6B;
		text-align: center;
	}

	.cta-container>.newsletter-cta h2 {
		color: #fff;
		margin-top: 0;
	}
	
	.cta-container .gform_footer {
		justify-content: flex-end;
	}
	
	@media only screen and (max-width: 768px) {
		.cta-container {
			display: block;
		}
		
		.cta-container>.newsletter-cta h2 {
			color: #fff;
			margin-top: 0;
			font-size: min(24px, 10vw);
		}
		
		.cta-container>.newsletter-cta {
			padding-left: 2em;
			padding-right: 2em;
			padding-bottom: 2em;
		}
	}

</style>

<div class="cta-container">

	<div class="newsletter-cta">
		
		<p class="subheading white">Sign Up</p>
		<h2>Sign up to our e-updates</h2>

		<?= do_shortcode('[gravityform id="1" title="false" ajax="true"]' )?>

	</div>

	<div class="map">
		
<!---	<iframe width="100%" loading="lazy" height="100%" id="gmap_canvas" src="https://maps.google.com/maps?q=Unit%2011%20Pipers%20Wood%20Industrial%20Park,%20Waterberry%20Drive,%20Waterlooville%20PO7%207XU&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe> -->
		
<iframe id="gmap_canvas"src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d1561.3221151476764!2d-1.0443232213217315!3d50.88439678983062!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2suk!4v1680533874520!5m2!1sen!2suk&output=embed" width="100%" height="100%" loading="lazy" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" title="Google Map of Hampshire Flag location"></iframe>	
	
		

	</div>

</div>

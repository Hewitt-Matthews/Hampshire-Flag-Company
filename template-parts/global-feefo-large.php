<style>

	.review-heading {
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 3em;
		padding: 1em 0 3em;
		border-bottom: solid 1px #000000;
		margin-bottom: 4em;
	}
	
	.review-heading h2 {
		margin: 0;
	}
	
	.mobile-feefo {
		display: none;
	}
	
	@media only screen and (max-width: 768px) {
		.review-heading {
			padding: 0em 0 3em!important;
		}
		.service-carousel-container.mobile {
			width: 320px!important;
		    justify-content: left!important;
		}	
		.mobile-service-rating-summary {
			width: 295px!important;
			padding-left: 5px!important;
		}
		.mobile-feefo {
			display: block;
		}
		.feefo-review-carousel-widget-service {
			display: none;
		}
	}
	
</style>

<div class="review-heading">
	<h2>What our customers say</h2>
	<a href="https://www.feefo.com/en_GB/reviews/hampshire-flag?displayFeedbackType=BOTH&timeFrame=YEAR" target="blanl" class="primary-btn">All Reviews</a>
</div>

<div id="feefo-service-review-carousel-widgetId" class="feefo-review-carousel-widget-service"></div>


<style>

	#feefo-small-widget > div {
		padding-bottom: 0;
	}

	.simple-feefo-container {
		background-color: #F8F9FA;
		padding: 2em;
		text-align: center;
	}
	
	.simple-feefo-container p {
		margin: 0;
	}

	.simple-feefo-container>div>p {
		font-weight: 600;
		font-size: 24px;
	}
	
	.simple-feefo-container > div {
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: wrap;
		gap: 1em;
	}
	
	.simple-feefo-container > div img {
		max-width: 150px;
	}

	.star {
		  display: inline-block;
		  clip-path: polygon(50% 0%, 65% 31%, 98% 35%, 73% 58%, 79% 91%, 49% 74%, 21% 91%, 27% 58%, 2% 35%, 35% 31%);
		  width: 35px;
		  height: 35px;
		  background-color: #fbde01;
	}
	
</style>

<div class="simple-feefo-container mobile-feefo">

	<div>

		<div class="stars">
			<?php for($i = 0; $i < 5; $i++) : ?>
				<span class='star'></span>
			<?php endfor; ?>
		</div>
		<p>4.8 / 5</p>
		<?= wp_get_attachment_image( 507369, "large", true, array( "loading" => "lazy" ) ); ?>

	</div>
	<p>Independant feedback based on <strong>200+</strong> verified reviews</p>
	
</div>
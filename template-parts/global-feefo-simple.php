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
		  clip-path: polygon(50% 0%, 65% 31%, 98% 35%, 73% 58%, 79% 91%, 49% 74%, 21% 91%, 27% 58%, 2% 35%, 35% 31%);
		  width: 25px;
		  height: 25px;
		  background-color: #fbde01;
	}
	
</style>

<div class="simple-feefo-container">

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
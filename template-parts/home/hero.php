<style>

	.home-hero .intro {
		display: flex;
		height: 70vh;
	}
	
	.home-hero .intro .left-side {
		background-color: rgb(var(--secondary));
		display: flex;
		flex: 1 1 33%;
		align-items: center;
		padding: min(5em, 10vw);
		color: #fff;
	}
	
	.home-hero .intro .left-side>div {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
	}
	
	.home-hero .intro .left-side h1 {
		font-size: min(36px, 11vw);
		color: #fff;
	}
	
	.home-hero .intro .right-side {
		flex: 1 1 50%;
	}
	
	@media only screen and (max-width: 980px) {
	
		.home-hero .intro {
			display: flex;
			height: 80vh;
		}	
		
		.home-hero .intro .left-side {
			padding: 2em 1em 3em 1em;
		}
		
		.home-hero .intro .left-side h1 {
			font-size: min(28px, 11vw);
			color: #fff;
			line-height: 1.3em;
		}	
		
		.home-hero .intro {
			flex-direction: column;
		}
		
		.home-hero .intro .right-side {
/* 			display: none; */
		}
		
	}
	
</style>


<div class="home-hero">
					
	<div class="intro">
		
		<div class="left-side">
			<?php get_template_part('template-parts/home/hero', 'intro') ?>
		</div>

		<div class="right-side">
			<?php get_template_part('template-parts/home/hero', 'slider') ?>
		</div>
		
	</div>

	<?php get_template_part('template-parts/home/hero', 'hfc-reasons') ?>

</div>
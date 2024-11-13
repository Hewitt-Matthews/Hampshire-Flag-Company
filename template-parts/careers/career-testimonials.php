<?php

$careerTestimonials = get_field('career_testimonials');

if ($careerTestimonials) : ?>

<style>

	.testimonials-container {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(400px, 100%), 1fr));
		gap: 4em;
		padding: 50px 50px!important;
	}
	.testimonials-container .testimonial {
		display: grid;
		grid-template-columns: auto 1fr;
		margin-bottom: 40px;
	}
	.testimonials-container .testimonial .content {
		padding-left: 10px!important;
	}
	.testimonials-container .testimonial .content h4 {
		font-weight: bold!important;
		text-transform: uppercase!important;
		padding-bottom: 20px!important;
	}
	.testimonials-container .testimonial .content h5 {
		font-weight: bold;
		text-transform: uppercase!important;
		padding-bottom: 20px!important;
		color: #CE0024!important;
	}
	
	@media only screen and (max-width: 768px) {
		.testimonials-container .testimonial {
			display: block;
		}
	}
	
</style>

<div class="testimonials-container">

	<?php foreach ( $careerTestimonials as $testimonial ) :
	
		$name = $testimonial['testimonial_name'];
		$position = $testimonial['testimonial_position'];
		$text = $testimonial['testimonial_text'];
		$icon = $testimonial['testimonial_icon'];

	?>

		<div class="testimonial">
			
			<img decoding="async" height="120px" width="120px" class="ls-is-cached lazyloaded" src="<?= $icon ?>">
			
			<div class="content">
				<h4><?= $name ?></h4>
				<h5><?= $position ?></h5>
				<?= $text ?>
			</div>
			
		</div>
	
	<?php endforeach; ?>
	
</div>

<?php endif; ?>
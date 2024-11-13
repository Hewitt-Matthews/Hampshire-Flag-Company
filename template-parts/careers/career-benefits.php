<?php

$careerBenefits = get_field('career_benefits');

if ($careerBenefits) : ?>

<style>

	.benefits-container {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(400px, 100%), 1fr));
		gap: 2em;
		padding: 50px 100px!important;
	}
	.benefits-container .benefit {
		display: grid;
		grid-template-columns: auto 1fr;
		margin-bottom: 40px;
	}
	.benefits-container .benefit .content {
		padding-left: 10px!important;
	}
	.benefits-container .benefit .content h4 {
		font-weight: bold!important;
		text-transform: uppercase!important;
		padding-bottom: 20px!important;
	}
	
</style>

<div class="benefits-container">

	<?php foreach ( $careerBenefits as $benefit ) :
	
		$title = $benefit['benefit_title'];
		$text = $benefit['benefit_description'];

	?>

		<div class="benefit">
			
			<img decoding="async" height="17px" width="17px" data-src="/wp-content/uploads/2023/01/arrow-red.svg" class="ls-is-cached lazyloaded" src="/wp-content/uploads/2023/01/arrow-red.svg">
			
			<div class="content">
				<h4><?= $title ?></h4>
				<?= $text ?>
			</div>
			
		</div>
	
	<?php endforeach; ?>
	
</div>

<?php endif; ?>
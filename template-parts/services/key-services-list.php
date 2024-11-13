<style>

	.services-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(100%, 410px), 1fr));
		gap: 0 1em;
		grid-auto-rows: 1fr;
		grid-auto-flow: row dense;
	}
	
	.services-grid .service {
		border-bottom: solid 1px!important;
		text-transform: uppercase;
		letter-spacing: 1px;
		list-style: none!important;
		font-size: 18px;
		padding: 1em 0!important;
	}

</style>

<?php

$listed_services = get_field('service_list');

if ( $listed_services ) : ?>

	<div class="services-grid">

		<?php foreach($listed_services as $service) : ?>

			<div class="service"><?= $service['listed_service'] ?></div>

		<?php endforeach; ?>

	</div>

<?php else : ?>

	<p>There are currently no services to show, please check back later.</p>

<?php endif; ?>
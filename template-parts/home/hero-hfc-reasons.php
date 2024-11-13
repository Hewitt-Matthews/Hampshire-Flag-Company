<style>

	.hfc-reasons {
		background-color: rgb(var(--primary));
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(200px, 100%), 1fr));
		padding: 0.5em 3em;
		text-align: center;
	}
	
	.hfc-reasons p {
		padding: 1em;
		margin: 0;
		color: #fff;
		text-transform: uppercase;
		letter-spacing: 1px;
		font-weight: 500;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	
	@media only screen and (min-width: 768px) {
		.hfc-reasons p:nth-child(n+2) {
			border-left: solid 1px #fff;   
		}
	}
	
	@media only screen and (max-width: 768px) {
		
		.hfc-reasons {
			grid-template-columns: 1fr;
		}
		
		.hfc-reasons p {
			border-bottom: solid 1px #fff;   
		}
		
		.hfc-reasons p:last-child {
			border-bottom: none;
		}
		
	}

</style>

<?php

$reasons = get_field('reasons_to_use_hfc');

?>

<div class="hfc-reasons">
	
	<?php foreach($reasons as $reason) : ?>
	
		<p><?= $reason['reason'] ?></p>
	
	<?php endforeach; ?>
	
</div>
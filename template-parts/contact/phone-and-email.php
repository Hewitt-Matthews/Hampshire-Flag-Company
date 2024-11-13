<?php 

$hfc_phone = get_field('hfc_phone_number', 'option');
$hfc_email = get_field('hfc_email_address', 'option');

?>

<style>

	#page #primary .phone-and-email {
		display: grid;
		gap: 2em;
	}
	
	#page #primary .phone-and-email > div {
		display: flex;	
	}
	
	#page #primary .phone-and-email > div h3 {
		text-transform: uppercase;
		font-weight: 600;
		font-size: min(20px, 8vw);
	}
	
	#page #primary .phone-and-email > div .icon-container {
		background-color: rgb(var(--secondary));
		padding: 10px;
		border-radius: 50%;
		line-height: 0;
		margin-right: 1em;
	}
	
	#page #primary .phone-and-email > div img {
		--iconSize: 35px;		
		width: var(--iconSize);
		height: var(--iconSize);
	}

</style>

<div class="phone-and-email">
	
	<div>
		
		<div class="icon-container">
			<img src="<?= get_stylesheet_directory_uri() ?>/assets/call-icon.svg" alt="Phone Icon">
		</div>
			
		<div>
			<h3>Telephone</h3>
			<p><?= $hfc_phone ?></p>
		</div>
		
	</div>
	
	<div>
		
		<div class="icon-container">
			<img src="<?= get_stylesheet_directory_uri() ?>/assets/email-icon.svg" alt="Email Icon">
		</div>
		
		<div>
			<h3>Email</h3>
			<p><?= $hfc_email ?></p>
		</div>
		
	</div>
	
</div>
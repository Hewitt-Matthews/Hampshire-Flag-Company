<?php

$address = get_field('hfc_address', 'option');
$hours = get_field('sales_and_support_availability', 'option');

?>

<style>
	
	
	#page #primary .contact-info {
		display: grid;
	}
	
	#page #primary .contact-info iframe {
		height: 350px;
	}

	
	#page #primary .contact-info .address-and-hours {
		background-color: rgb(var(--secondary));
		padding: 2em;
		color: #fff;
	}
	
	#page #primary .contact-info .address-and-hours h3 {
		text-transform: uppercase;
		font-weight: 600;
		letter-spacing: 1px;
		margin-top: 2em;
	}


</style>

<div class="contact-info">
	

	<iframe width="100%" height="100%" id="gmap_canvas" src="https://maps.google.com/maps?q=Unit%2011%20Pipers%20Wood%20Industrial%20Park,%20Waterberry%20Drive,%20Waterlooville%20PO7%207XU&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
	
	<div class="address-and-hours">
		
		<p><?= $address ?></p>
		<a href="https://www.google.com/maps/dir/Unit%2011%20Pipers%20Wood%20Industrial%20Park,%20Waterberry%20Drive,%20Waterlooville%20PO7%207XU/" target="_blank" class="primary-btn">Get Directions</a>
		
		<h3>Opening Hours</h3>
		<p><?= $hours ?></p>
		
	</div>
	
</div>

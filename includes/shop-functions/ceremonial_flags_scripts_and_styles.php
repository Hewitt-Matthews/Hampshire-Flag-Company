<?php

function ceremonial_flags_scripts() {
	
	?>

        <script>
			
			window.addEventListener('load', () => {
				
				const formLabels = document.querySelectorAll('.product form .gfield_label');
				formLabels.forEach(label => {
					
					label.parentElement.setAttribute('data-field', label.textContent);
					
				})
				
				//Code to set the preselected options
				const sizeOption = document.querySelector('.gchoice.gchoice_19_3_0 input');
				if(sizeOption) sizeOption.click();
				
				const fringeOption = document.querySelector('.gchoice.gchoice_19_8_1 input');
				if(fringeOption) fringeOption.click();

			})

        </script>

	<?php
	
}


function ceremonial_flag_scripts_init() {
	
	$product_id = get_queried_object_id();

	if ( $product_id == 517042 ) {
		add_action( 'wp_footer', 'ceremonial_flags_scripts' );
	} 
	
}
add_action( 'woocommerce_before_single_product', 'ceremonial_flag_scripts_init' );
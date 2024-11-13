<?php

function coffin_drapes_scripts() {
	
	?>

        <script>
			
			window.addEventListener('load', () => {
				
				const formOptions = document.querySelectorAll('.gfield-choice-input[type="radio"]');
				
				const resetCountrySelectBoxValue = (e) => {
					
					const selectBox = document.querySelector('.gfield--input-type-select[data-conditional-logic="visible"] select');
					const selectedCountry = selectBox.value.split('|')[0];
					
					setTimeout(() => {
						
						const newSelectBox = document.querySelector('.gfield--input-type-select[data-conditional-logic="visible"] select');
						//const selectedOption = newSelectBox.querySelector(`option[value="${selectedCountry}"]`);
						
						const options = Array.from(newSelectBox.options);
						const selectedOption = options.find(option => option.textContent === selectedCountry);

						if(selectedOption) {
							
							selectedOption.selected = true;

							// Create a new 'change' event
							var event = new Event('change', { bubbles: true });

							// Dispatch the event
							newSelectBox.dispatchEvent(event);
							
						}					
						
					}, 100)
									
				}
				
				
				formOptions.forEach(option => {
					
					option.addEventListener('click', resetCountrySelectBoxValue)
					
				})

			})

        </script>

	<?php
	
}


function coffin_drapes_scripts_init() {
	
	$product_id = get_queried_object_id();

	if ( $product_id == 523676 ) {
		add_action( 'wp_footer', 'coffin_drapes_scripts' );
	} 
	
}
add_action( 'woocommerce_before_single_product', 'coffin_drapes_scripts_init' );
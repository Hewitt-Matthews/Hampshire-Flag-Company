<?php

function custom_handwaver_standard_size_scripts() {
	
	?>

		<style>

			.per-flag-text {
				display: inline-block;
				font-weight: 500;
				color: rgb(var(--secondary));
				margin-bottom: 0;
			}

			.single-product div.product p.price,
			.gfield--input-type-singleproduct {
				position: absolute;
				opacity: 0;
				z-index: -1;
				transform: scale(0);
			}
			
			span.formattedTotalPrice.ginput_total {
				font-size: 22px!important;
			}
			
			.total-calculated-price {
				font-size: 32px;
				font-weight: 500;
				color: rgb(var(--secondary));
			}
			
			p.total-calculated-price::before {
				content: "\00A3";
			}

		</style>

        <script>
				
				//
				//
				//Custom handwavers Quantity Flag Update
				//
				//

				//Get Custom Quantity Field
				const customQuanityField = document.querySelector('#field_37_4.gfield--type-number input');
				customQuanityField.step = 10;
				customQuanityField.value = 10;
				customQuanityField.min = 10;
				
				//Click the first input
				document.querySelector('.gfield-choice-input').click();
			
				//Get the default Quantity Field
				const defaultQuanityField = document.querySelector('input[name="quantity"]');
				defaultQuanityField.parentElement.setAttribute('style', 'display: none');
				defaultQuanityField.value = 10; 
			
				//
				//
				//Custom handwavers update quantity field step size based on item
				//
				//
				
				//Get all the material fields
				const materialFields = document.querySelectorAll('fieldset#field_37_3 input');
				
				//If paper field is pressed, update quantity step size to 1 but min value of 250
				//Else set step size to 10 and value to 10
				
				const updateQuantityField = (e) => {
					
					const selectedMaterial = e.currentTarget.value.replace(/\s/g, "-").trim();
					const normalizedSelectedMaterial = selectedMaterial.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // this will remove any accent
					const target = 'Paper-Handwaver-/-Paper-Stick'.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // normalize and clean target as well

					if(normalizedSelectedMaterial === target) {
						customQuanityField.step = 1;
						customQuanityField.value = 250;
						customQuanityField.min = 250;
						defaultQuanityField.value = 250;
					} else {
						customQuanityField.step = 10;
						customQuanityField.value = 10;
						customQuanityField.min = 10;
						defaultQuanityField.value = 10; 
					}
					
				}

				materialFields.forEach(material => {
					
					material.addEventListener('click', updateQuantityField);

				})
			
				//Create an event listenter to update the value of the default quantity field when the custom one is changes
				customQuanityField.addEventListener('change', (e) => {

					const quantity = e.target.value;
					defaultQuanityField.value = quantity; 
				
				})

			
				//
				//
				//Custom handwavers Update price to read £xx.xx per car flag
				//
				//

				// Select the node that will be observed for mutations
				const dynamicPriceEl = document.querySelector('.product_totals ul > li:last-of-type > div');

				const perFlagTextEl = document.createElement('p');
				perFlagTextEl.textContent = ' per handwaver';
				perFlagTextEl.classList.add('per-flag-text');
				
				dynamicPriceEl.appendChild(perFlagTextEl);

				// Options for the observer (which mutations to observe)
				const config = { attributes: false, childList: true, subtree: true };

				// Callback function to execute when mutations are observed
				const createTotalPrice = (mutationList, observer) => {
					for (const mutation of mutationList) {
						if (mutation.type === "childList") {
							
							//
							//Calculate Total price and put it below 'per-item' price
							//
							
							const target = mutation.target;
							const splitPrice = target.innerHTML.split('').slice(1).join('');
							const calculatedPrice = parseFloat(splitPrice)
							
							const totalPrice = (customQuanityField.value * calculatedPrice).toFixed(2);
							
							const totalPriceCalculation = document.querySelector('.total-calculated-price');

							if(totalPriceCalculation) {

								totalPriceCalculation.textContent = totalPrice + " total";
								
							} else {

								const totalPriceCalculationEl = document.createElement('p');
								totalPriceCalculationEl.classList.add('total-calculated-price');
								totalPriceCalculationEl.textContent = totalPrice + " total";
								target.parentElement.after(totalPriceCalculationEl);

							}
							
						}
					}
				};

				// Create an observer instance linked to the callback function
				const createTotalPriceObserver = new MutationObserver(createTotalPrice);

				// Start observing the target node for configured mutations
				createTotalPriceObserver.observe(dynamicPriceEl, config);

        </script>

	<?php
	
}


function custom_standard_size_handwavers() {
	$pageID = get_queried_object_id();
		if($pageID == 524985) {
			// execute code to run on this specific page
			add_action( 'wp_footer', 'custom_handwaver_standard_size_scripts' );
		}
}
add_action( 'wp', 'custom_standard_size_handwavers' );
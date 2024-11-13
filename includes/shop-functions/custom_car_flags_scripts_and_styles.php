<?php

add_action( 'wp_footer', 'custom_car_flags_scripts_and_styles' );

function custom_car_flags_scripts_and_styles() {
	
    $pageID = get_queried_object_id();

    if($pageID == 524922) : ?>

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
			
			if(document.body.classList.contains('postid-524922')) {
				
				//
				//
				//Custom Car Flags Quantity Flag Update
				//
				//

				//Get Custom Quantity Field
				const customQuanityField = document.querySelector('.gfield--type-number input');

				//Get the default Quantity Field
				const defaultQuanityField = document.querySelector('input[name="quantity"]');
				defaultQuanityField.parentElement.setAttribute('style', 'display: none');

				//Create an event listenter to update the value of the default quantity field when the custom one is changes
				customQuanityField.addEventListener('change', (e) => {

					const quantity = e.target.value;
					defaultQuanityField.value = quantity;

				})

				//
				//
				//Custom Car Flags Update price to read £xx.xx per car flag
				//
				//

				// Select the node that will be observed for mutations
				const dynamicPriceEl = document.querySelector('.product_totals ul > li:last-of-type > div');

				const perFlagTextEl = document.createElement('p');
				perFlagTextEl.textContent = ' per car flag';
				perFlagTextEl.classList.add('per-flag-text');
				
				dynamicPriceEl.appendChild(perFlagTextEl);

				// Options for the observer (which mutations to observe)
				const config = { attributes: false, childList: true, subtree: true };

				// Callback function to execute when mutations are observed
				const createTotalPrice = (mutationList, observer) => {
					for (const mutation of mutationList) {
						if (mutation.type === "childList") {
							
							const target = mutation.target;
							const splitPrice = target.innerHTML.split('').slice(1).join('');
							const calculatedPrice = parseFloat(splitPrice)
							
							const totalPrice = (customQuanityField.value * calculatedPrice).toFixed(2);
							
							const totalPriceCalculation = document.querySelector('.total-calculated-price');

							if(totalPriceCalculation) {

								totalPriceCalculation.textContent = totalPrice;
								
							} else {

								const totalPriceCalculationEl = document.createElement('p');
								totalPriceCalculationEl.classList.add('total-calculated-price');
								totalPriceCalculationEl.textContent = totalPrice;
								target.parentElement.after(totalPriceCalculationEl);

							}

							//console.log(`The new list has ${mutation.addedNodes[0].children[0].childElementCount} chilren.`);
						}
					}
				};

				// Create an observer instance linked to the callback function
				const createTotalPriceObserver = new MutationObserver(createTotalPrice);

				// Start observing the target node for configured mutations
				createTotalPriceObserver.observe(dynamicPriceEl, config);

				//Set Default Options
				const defaultOptions = document.querySelectorAll('#choice_24_3_0, #choice_24_5_0');
				defaultOptions.forEach(option => {option.click()})
				customQuanityField.value = 1;
				customQuanityField.setAttribute('min', '0');
				customQuanityField.dispatchEvent(new Event('change'));
				
			}

        </script>
       
    <?php endif;

}
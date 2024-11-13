<?php

function custom_golf_pin_flags_scripts() {
	
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
			
			fieldset.discount-table-wrapper.gfield,
			.add-more-message {
				display: none;
			}

			.gform-body:has(#choice_47_1_2:checked) fieldset.discount-table-wrapper.gfield,
			.gform-body:has(#choice_47_1_2:checked) .add-more-message{
				display: block;
			}

			fieldset.discount-table-wrapper.gfield p {
				margin: 0;
				font-weight: 600;
				text-transform: uppercase;
			}
			fieldset.discount-table-wrapper.gfield p span {
				margin-left: 1em;
			}

		</style>

        <script>
				
				//
				//
				//Custom handwavers Quantity Flag Update
				//
				//

				//Get Custom Quantity Field
				const customQuantityField = document.querySelector('#field_47_9.gfield--type-number input');
				const quantityMessageEl = document.createElement('div');
				quantityMessageEl.classList.add('add-more-message');
				customQuantityField.after(quantityMessageEl);
			
				const productDiscounts = {
					"3 - 5": "5%",
					"6 - 8": "7.5%"
				}
				
				let discount = null;
			
				//Get the default Quantity Field
				const defaultQuanityField = document.querySelector('input[name="quantity"]');
				defaultQuanityField.parentElement.setAttribute('style', 'display: none');

				//Create an event listenter to update the value of the default quantity field when the custom one is changes
				customQuantityField.addEventListener('change', (e) => {

					const quantity = e.target.value;
					defaultQuanityField.value = quantity;
					
					for (const range in productDiscounts) {
						const [min, max] = range.split(' - ');
						if(quantity < parseInt(min)) {
							discount = null;
							quantityMessageEl.textContent = `Add ${parseInt(min) - quantity} more to your basket to qualify for a 5% discount to this item.`;
							break;
						}

						if (quantity >= parseInt(min) && quantity <= parseInt(max)) {
							discount = productDiscounts[range];

							if(discount == "7.5%") {
								quantityMessageEl.textContent = `You're currently getting ${discount} off this item.`;
							} else {
								quantityMessageEl.textContent = `You're currently getting ${discount} off this item! Add ${(parseInt(max) + 1) - quantity} more to your basket to qualify for a further discount to this item.`;
							}

							break;
						} else {
							quantityMessageEl.textContent = 'No discount available for this quantity';
						}
					}

				})
			
				//Get 'Individual' input and place discount table below if selected
				const individualInput = document.querySelector('#choice_47_1_2');
			
				const discountTableEl = document.createElement('fieldset');
				discountTableEl.classList.add('discount-table-wrapper');
				discountTableEl.classList.add('gfield');
					const discountTableHeading = document.createElement('legend');
					discountTableHeading.classList.add('gfield_label');
					discountTableHeading.textContent = 'Individual Flag Discounts';
					discountTableEl.appendChild(discountTableHeading);
			
					// Loop through each item in the object
					for (const key in productDiscounts) {
					  if (productDiscounts.hasOwnProperty(key)) {
						const value = productDiscounts[key];

						// Create a <p> tag
						const p = document.createElement("p");

						// Set the text content of the <p> tag to "Object Key: Object Value"
						p.innerHTML = `${key}: <span>${value} off</span>`;

						// Append the <p> tag to the container element
						discountTableEl.appendChild(p);
					  }
					}

// 				individualInput.closest('fieldset').after(discountTableEl);
				//
				//
				//Custom handwavers Update price to read £xx.xx per car flag
				//
				//

				// Select the node that will be observed for mutations
				const dynamicPriceEl = document.querySelector('.product_totals ul > li:last-of-type > div');

				const perFlagTextEl = document.createElement('p');
				perFlagTextEl.textContent = ' per golf pin flag';
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
							
							const totalPrice = (customQuantityField.value * calculatedPrice).toFixed(2);
							
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


function custom_golf_pin_flags() {
	$pageID = get_queried_object_id();
		if($pageID == 542216) {
			// execute code to run on this specific page
			add_action( 'wp_footer', 'custom_golf_pin_flags_scripts' );
		}
}
add_action( 'wp', 'custom_golf_pin_flags' );
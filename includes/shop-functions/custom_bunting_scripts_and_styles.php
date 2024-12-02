<?php

add_action( 'wp_footer', 'custom_bunting_scripts_and_styles' );

function custom_bunting_scripts_and_styles() {
	
    $pageID = get_queried_object_id();

    if($pageID == 524934) : ?>

		<style>
			.lds-facebook {
			  display: inline-block;
			  position: relative;
			  width: 80px;
			  height: 80px;
			}
			.lds-facebook div {
			  display: inline-block;
			  position: absolute;
			  left: 8px;
			  width: 16px;
			  background: rgb(var(--secondary));
			  animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
			}
			.lds-facebook div:nth-child(1) {
			  left: 8px;
			  animation-delay: -0.24s;
			}
			.lds-facebook div:nth-child(2) {
			  left: 32px;
			  animation-delay: -0.12s;
			}
			.lds-facebook div:nth-child(3) {
			  left: 56px;
			  animation-delay: 0;
			}
			@keyframes lds-facebook {
			  0% {
				top: 8px;
				height: 64px;
			  }
			  50%, 100% {
				top: 24px;
				height: 32px;
			  }
			}

			.per-length-text {
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

			.visible-prices .unit-price {
				font-size: 20px!important;
				font-weight: 500;
				color: rgb(var(--secondary));
			}
			
			.visible-prices .unit-price span {
				font-size: 16px!important;
			}
			
			.visible-prices .total-price {
				font-size: 32px;
				font-weight: 500;
				color: rgb(var(--secondary));
			}
			
			.visible-prices .total-price::before {
				content: "\00A3";
			}

			.add-more-message {
				margin-top: 10px;
				font-style: italic;
				color: rgb(var(--secondary));
			}

			.product_totals .ginput_container {
				font-size: 22px;
				color: rgb(var(--secondary));
			}

			.product_totals .ginput_container .per-length-text {
				font-size: 16px;
				margin-left: 3px;
			}

		</style>

        <script>
			
			if(document.body.classList.contains('postid-524934')) {
				
				//Add loading spinner to form, remove when page is loaded
				
				const form = document.querySelector('form.cart');
				
				const loadScreenEl = document.createElement('p');
				loadScreenEl.textContent = 'Please wait while the calculator loads.';
				
					const loadingIcon = document.createElement('div');
					loadingIcon.classList.add('lds-facebook');
				
						for(let i = 0; i <= 2; i++) {
							const div = document.createElement('div');
							loadingIcon.appendChild(div);
						}
				
					loadScreenEl.appendChild(loadingIcon);
				
				loadScreenEl.classList.add('loading-screen');
				
				form.appendChild(loadScreenEl);
				
				window.addEventListener('load', () => { loadScreenEl.remove() })
				
				//
				//
				//Custom Bunting Quantity Flag Update
				//
				//

				//Get Custom Quantity Field
				const customQuanityField = document.querySelector('.gfield--type-number input');
				customQuanityField.value = 1;

				//Get the default Quantity Field
				const defaultQuanityField = document.querySelector('input[name="quantity"]');
				defaultQuanityField.parentElement.setAttribute('style', 'display: none');
				//defaultQuanityField.disabled = true;
				
				let isProgrammaticChange = false;

				defaultQuanityField.addEventListener('change', (e) => {
					if (isProgrammaticChange) {
						isProgrammaticChange = false;
						return;
					}

					const quantity = e.target.value;
					isProgrammaticChange = true;
					customQuanityField.value = quantity;
				});

				customQuanityField.addEventListener('change', (e) => {
					if (isProgrammaticChange) {
						isProgrammaticChange = false;
						return;
					}

					const quantity = e.target.value;
					isProgrammaticChange = true;
					defaultQuanityField.value = quantity;
				});

				//
				//
				//Custom Bunting Update price to read £xx.xx per car flag
				//
				//

				// Select the node that will be observed for mutations
				const dynamicPriceEl = document.querySelector('.product_totals ul > li:last-of-type > div');

				const perFlagTextEl = document.createElement('p');
				perFlagTextEl.textContent = ' per length';
				perFlagTextEl.classList.add('per-length-text');
				
				dynamicPriceEl.appendChild(perFlagTextEl);

				// Options for the observer (which mutations to observe)
				const config = { attributes: false, childList: true, subtree: true };

				// Callback function to execute when mutations are observed
				const createTotalPrice = (mutationList, observer) => {
					for (const mutation of mutationList) {
						if (mutation.type === "childList") {
							
							const target = mutation.target;
							const splitPrice = target.innerHTML.split('').slice(1).join('');
							const calculatedPrice = parseFloat(splitPrice);

							const totalPrice = (customQuanityField.value * calculatedPrice).toFixed(2);
							
							const totalPriceCalculation = document.querySelector('.total-calculated-price');

							if(totalPriceCalculation) {

								totalPriceCalculation.textContent = totalPrice;
								
							} else {

								const totalPriceCalculationEl = document.createElement('p');
								totalPriceCalculationEl.classList.add('total-calculated-price');
								totalPriceCalculationEl.textContent = totalPrice;
								target.parentElement.after(totalPriceCalculationEl)

							}

							//console.log(`The new list has ${mutation.addedNodes[0].children[0].childElementCount} chilren.`);
						}
					}
				};

				// Create an observer instance linked to the callback function
				const createTotalPriceObserver = new MutationObserver(createTotalPrice);

				// Start observing the target node for configured mutations
				createTotalPriceObserver.observe(dynamicPriceEl, config);

				window.addEventListener('load', () => {
					// Define all our DOM elements at the top
					const customQuanityField = document.querySelector('.gfield--type-number input');
					const defaultQuanityField = document.querySelector('input[name="quantity"]');
					const totalPriceCalculation = document.querySelector('.total-calculated-price');
					const formattedTotalPrice = document.querySelector('.formattedTotalPrice');
					const dynamicPriceEl = document.querySelector('.product_totals ul > li:last-of-type > div');

					// Define discount tiers
					const productDiscounts = {
						"1 - 2": "0%",
						"3 - 4": "2.5%",
						"5 - 9": "5%",
						"10 - 49": "7.5%",
						"50 - 249": "10%",
						"250 - 9999999": "12.5%"
					};

					function getBasePrice() {
						// Get the initial per-length price from the dynamic price element
						if (dynamicPriceEl) {
							const priceText = dynamicPriceEl.textContent.replace('£', '');
							const basePrice = parseFloat(priceText);
							console.log('Initial per-length price:', basePrice);
							return basePrice;
						}
						
						console.warn('Dynamic price element not found');
						return null;
					}

					function getDiscount(quantity) {
						console.log('Checking discount for quantity:', quantity);
						for (const [range, discount] of Object.entries(productDiscounts)) {
							const [min, max] = range.split(' - ').map(num => parseInt(num));
							console.log(`Checking range ${min}-${max}, discount: ${discount}`);
							if (quantity >= min && quantity <= max) {
								console.log('Found matching discount:', discount);
								return discount;
							}
						}
						console.log('No matching discount found, returning 0%');
						return "0%";
					}

					function updatePrices() {
						const quantity = parseInt(customQuanityField.value) || 1;
						const basePrice = getBasePrice();
						const discount = getDiscount(quantity);
						
						console.log('Base Price:', basePrice);
						console.log('Selected Discount:', discount);
						console.log('Quantity:', quantity);

						if (!basePrice) {
							console.error('Base price not found in Gravity Forms');
							return;
						}

						let discountedUnitPrice = basePrice;
						if (discount !== "0%") {
							const currentDiscount = parseFloat(discount.replace("%", ""));
							const discountAmount = (basePrice / 100) * currentDiscount;
							discountedUnitPrice = (basePrice - discountAmount).toFixed(2);
							console.log('Discount Amount:', discountAmount);
							console.log('Discounted Unit Price:', discountedUnitPrice);
						} else {
							console.log('No discount applied');
						}

						const totalPrice = (quantity * discountedUnitPrice).toFixed(2);
						console.log('Total Price:', totalPrice);

						// Update displays
						if (totalPriceCalculation) {
							totalPriceCalculation.textContent = totalPrice;
						}

						if (formattedTotalPrice) {
							formattedTotalPrice.textContent = `£${totalPrice}`;
						}

						if (dynamicPriceEl) {
							dynamicPriceEl.innerHTML = `£${discountedUnitPrice}`;
							const perLengthText = document.querySelector('.per-length-text');
							if (perLengthText) {
								dynamicPriceEl.appendChild(perLengthText);
							}
						}

						// Add message about current discount if applicable
						const existingMessage = document.querySelector('.add-more-message');
						if (existingMessage) {
							existingMessage.remove();
						}

						if (discount !== "0%") {
							const messageEl = document.createElement('p');
							messageEl.classList.add('add-more-message');
							messageEl.textContent = `A ${discount} discount has been applied!`;
							dynamicPriceEl.parentElement.appendChild(messageEl);
						}
					}

					// Attach event listener to update prices
					if (customQuanityField) {
						customQuanityField.addEventListener('input', updatePrices);
						// Initial calculation
						updatePrices();
					}
				})
			}

        </script>
       
    <?php endif;

}

function display_custom_starting_price_above_description() {
    global $product;

    // Check if the current product's ID is 524934
    if ($product->get_id() == 524934) {
        // Manually set the starting price or retrieve it from a custom field
        $starting_price = '£7.36'; // Replace this with your custom logic to get the starting price

        // Display the starting price above the description
        echo '<span class="price"><span class="starting-from">Starting from <span class="amount">' . esc_html($starting_price) . '</span></span></span>';
    }
}
add_action('woocommerce_single_product_summary', 'display_custom_starting_price_above_description', 5);
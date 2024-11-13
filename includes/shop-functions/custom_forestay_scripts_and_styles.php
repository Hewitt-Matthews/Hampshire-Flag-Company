<?php

add_action( 'wp_footer', 'custom_forestay_scripts_and_styles' );

function custom_forestay_scripts_and_styles() {
	
    $pageID = get_queried_object_id();

    if($pageID == 524919) : ?>

		<style>

			.per-flag-text {
				display: inline-block;
				font-weight: 500;
				color: rgb(var(--secondary));
				margin-bottom: 0;
				font-size: 16px;
			}

			.single-product div.product p.price,
			.gfield--input-type-singleproduct {
				position: absolute;
				opacity: 0;
				z-index: -1;
				transform: scale(0);
			}
			
			span.formattedTotalPrice.ginput_total,
			.unit-price {
				font-size: 22px!important;
				color: rgb(var(--secondary));
			}
			
			.total-calculated-price,
			.total-price  {
				font-size: 32px;
				font-weight: 500;
				color: rgb(var(--secondary));
			}
			
			p.total-calculated-price::before,
			.total-price::before {
				content: "\00A3";
			}

		</style>

        <script>
			
			if(document.body.classList.contains('postid-524919')) {
				
				window.addEventListener('load', () => {
				
					//Discount Object

					const productDiscounts = {
						"2 - 4": "5%",
						"5 - 9": "7.5%",
						"10 - 19": "10%",
						"20 - 29": "12.5%",
						"30 - 49": "15%",
						"50 - 9999999": "17.5%"
					};

					//
					//
					//Custom Flags Price Logic
					//
					//
					let discount = null;

					// Select the node that will be observed for mutations
					const dynamicPriceEl = document.querySelector('.product_totals ul > li:last-of-type > div span');

					const perFlagTextEl = document.createElement('p');
					perFlagTextEl.textContent = ' per flag';
					perFlagTextEl.classList.add('per-flag-text');

					dynamicPriceEl.appendChild(perFlagTextEl);
					
					//Delay the clone to reliably access the price
					setTimeout(() => { 

						// Clone Price Section
						const clone = dynamicPriceEl.closest('li').cloneNode();
						clone.classList.add('visible-prices');
							const cloneTitle = document.createElement('label');
							cloneTitle.classList.add('gfield_label')
							cloneTitle.textContent = "Total";
							clone.appendChild(cloneTitle);

							const cloneUnitPrice = document.createElement('div');
							cloneUnitPrice.classList.add('unit-price')
							cloneUnitPrice.textContent = document.querySelector('.formattedTotalPrice').innerText;
								cloneUnitPrice.appendChild(perFlagTextEl);
							clone.appendChild(cloneUnitPrice);
						
							const splitPrice = cloneUnitPrice.innerHTML.split('').slice(1).join('');
							const calculatedPrice = parseFloat(splitPrice)

							const cloneTotalPrice = document.createElement('div');
							cloneTotalPrice.classList.add('total-price')
							cloneTotalPrice.textContent = calculatedPrice;
							clone.appendChild(cloneTotalPrice);

						dynamicPriceEl.closest('ul').appendChild(clone);
				

						//Get Custom Quantity Field
						const customQuanityField = document.querySelector('#field_23_39.gfield--type-number input');
						const quantityMessageEl = document.createElement('div');
						quantityMessageEl.classList.add('add-more-message');
						customQuanityField.after(quantityMessageEl);

						//Get the default Quantity Field
						const defaultQuanityField = document.querySelector('input[name="quantity"]');
						defaultQuanityField.parentElement.setAttribute('style', 'display: none');

						//Create an event listenter to update the value of the default quantity field when the custom one is changes
						customQuanityField.addEventListener('change', (e) => {

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

									if(discount == "12.5%") {
										quantityMessageEl.textContent = `You're currently getting ${discount} off this item.`;
									} else {
										quantityMessageEl.textContent = `You're currently getting ${discount} off this item! Add ${(parseInt(max) + 1) - quantity} more to your basket to qualify for a further discount to this item.`;
									}

									break;
								}
							}

							//Update Price
							const unitPrice = document.querySelector('.formattedTotalPrice');

							const splitPrice = unitPrice.innerHTML.split('').slice(1).join('');
							const calculatedPrice = parseFloat(splitPrice);
							const unitPriceEl = document.querySelector('.unit-price');
							const totalPriceEl = document.querySelector('.total-price');
							let totalPrice;

							if(!discount) {
								const unitPrice = calculatedPrice;
								unitPriceEl.innerHTML = `&pound;${unitPrice.toFixed(2)} <span class="per-flag-text"> per flag</span>`;
								totalPrice = (customQuanityField.value * calculatedPrice).toFixed(2);
								totalPriceEl.textContent = totalPrice;
							} else {

								const currentDiscount = parseFloat(discount.replace("%", ""));
								const dicountAmount = (calculatedPrice / 100) * currentDiscount;

								const unitPrice = Math.ceil((calculatedPrice - dicountAmount) * 100) / 100;
								unitPriceEl.innerHTML = `&pound;${unitPrice.toFixed(2)} <span class="per-flag-text"> per flag</span>`;

								totalPrice = (customQuanityField.value * unitPrice).toFixed(2);
								totalPriceEl.textContent = totalPrice;


							}

							//Observe changes in the unit price
							const observer = new MutationObserver(() => {

								//Get the current unit price
								const priceText = unitPrice.textContent.trim();
								// Remove any commas from the price text
								const cleanPriceText = priceText.replace(/,/g, '');
								const currentUnitPrice = parseFloat(cleanPriceText.slice(1));

								//Calculate the total price based on the quantity and current unit price
								let totalPrice;

								if(!discount) {
									const unitPrice = currentUnitPrice;
									unitPriceEl.innerHTML = `&pound;${unitPrice.toFixed(2)} <span class="per-flag-text"> per flag</span>`;
									totalPrice = (customQuanityField.value * currentUnitPrice).toFixed(2);
									totalPriceEl.textContent = totalPrice;
								} else {
									const currentDiscount = parseFloat(discount.replace("%", ""));
									const discountAmount = (currentUnitPrice / 100) * currentDiscount;

									const unitPrice = Math.ceil((currentUnitPrice - discountAmount) * 100) / 100;
									unitPriceEl.innerHTML = `&pound;${unitPrice.toFixed(2)} <span class="per-flag-text"> per flag</span>`;

									totalPrice = (customQuanityField.value * unitPrice).toFixed(2);
									totalPriceEl.textContent = totalPrice;
								}
								
								/* Add Min Price */
								const buyButton = document.querySelector('button.single_add_to_cart_button');
								const minimumPrice = 25;
							
								if(totalPrice > minimumPrice) {

									const buttonMessage = document.querySelector('.valid-selection-message');
									if(buttonMessage) buttonMessage.remove();

								} else {

									const buttonMessage = document.querySelector('.valid-selection-message');

									if(!buttonMessage) {
										const buttonMessageEl = document.createElement('p');
										buttonMessageEl.classList.add('valid-selection-message');
										buttonMessageEl.innerHTML = `Please note this product has a minimum purchase price of &pound;${minimumPrice}. As your current total is below &pound;${minimumPrice}, we'll round that up in the checkout process.`;
										buyButton.after(buttonMessageEl)
									}

									//mutation.target.textContent = '£15.00';

								}
								
							});

							//Start observing the unit price for changes
							observer.observe(unitPrice, { childList: true, subtree: true });

						})

						//Set Default Options
						customQuanityField.value = 1;
						customQuanityField.setAttribute('min', '0');
						customQuanityField.dispatchEvent(new Event('change'));
						
					}, 1000);
					
				})
				
			}

        </script>
       
    <?php endif;

}
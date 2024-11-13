<?php

add_action( 'wp_footer', 'calculate_total_products' );

function calculate_total_products() {

	//If product isn't a custom product, use JS to update the diplayed price
	global $product;
	$is_custom_product = false;
	$categories_to_check = [759, 181, 779, 752];
	
	if(is_product()) {
		
		$product_category_ids = $product->get_category_ids();
		// Get all the ancestor category IDs of the categories to check
		$ancestor_category_ids = array();
		foreach ($categories_to_check as $category_id) {
			$ancestors = get_ancestors($category_id, 'product_cat');
			$ancestor_category_ids = array_merge($ancestor_category_ids, $ancestors);
		}
		$categories_to_check = array_merge($categories_to_check, $ancestor_category_ids);

		// Check if any of the category IDs match the categories to check
		if (count(array_intersect($product_category_ids, $categories_to_check)) > 0) {
			$is_custom_product = true;
		}

	}
	
	
    if (!$is_custom_product) : ?>

		<style>
			
			.woocommerce-variation-price {
				padding-top: 10px;
			}
			
			.woocommerce-variation-price .price .amount {
				font-size: 22px;
			}
			
			.per-flag-text {
				display: inline-block;
				font-size: 16px;
				font-weight: 500;
				color: rgb(var(--secondary));
				margin-bottom: 0;
				padding-left: 3px;
			}

			.total-text {
				font-size: 14px;
				font-weight: 500;
				color: #6d6d6d;
				text-transform: uppercase;
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
                display: block;
			}
			
			p.total-calculated-price::before {
				content: "\00A3";
			}

		</style>

        <script>
			
			if(document.body.classList.contains('single-product')) {
				
				window.addEventListener('load', () => {
					
					// Determine product type by identifying the price var
					const variableProductPrice = document.querySelector('.single_variation_wrap');
					const simpleProductPrice = document.querySelector('.summary > span.price');
                    const gravityProductPrice = document.querySelector('span.formattedTotalPrice.ginput_total');
					
					// If a nett product add nett after total price
					let nett = '';
					const productDiv = document.querySelector('.product');
					// Nett Products
					const productID = productDiv.id;
					const productIDStripped = productID.replace(/^product-/i, "");
					const nettProducts = [517322, 517333, 517321, 517323, 517065, 517059, 507585, 517354];
					// Nett Categories
					const nettCategories = ['ground-mounted-flagpoles', 'wall-mounted-flagpoles-subcategory', 'car-forecourt-flagpoles']; // - category 72 (ground mounted flagpoles);
					const divClasses = productDiv.classList;
					const productCatClasses = Array.from(divClasses).filter(className => className.startsWith('product_cat'));
					for (let i = 0; i < productCatClasses.length; i++) {
					  productCatClasses[i] = productCatClasses[i].replace('product_cat-', '');
					}
					const count = nettCategories.filter(category => productCatClasses.includes(category)).length;
					// If included in nettProducts OR nettCategories
					if (nettProducts.includes(parseInt(productIDStripped)) || count > 0 ) {
						nett = ' nett';
					}

					// If is a variable Product
					if(variableProductPrice) {
						
						//Check if default option has been checked, if so create per item text and total el
						const defaultPrice = document.querySelector('.single_variation_wrap .woocommerce-variation-price .price .amount');
						if(defaultPrice) {
							
							// add total text
							const priceWrapper = document.querySelector('.woocommerce-variation-price .price')

							const totalText = document.createElement('p');
							totalText.textContent = 'total';
							totalText.classList.add('total-text');

							priceWrapper.insertAdjacentElement('afterbegin', totalText);
							
							const totalPriceCalculationEl = document.createElement('p');
							totalPriceCalculationEl.classList.add('total-calculated-price');
							totalPriceCalculationEl.textContent = defaultPrice.innerHTML.split('').slice(1).join('');
							defaultPrice.parentElement.after(totalPriceCalculationEl);
							
							
							const perItemTextEl = document.createElement('p');
							perItemTextEl.textContent = ' per item';
							perItemTextEl.classList.add('per-flag-text');

							defaultPrice.appendChild(perItemTextEl);
							
						}

						// Options for the observer (which mutations to observe)
						const config = { attributes: false, childList: true, subtree: true };

						// Callback function to execute when mutations are observed
						const createTotalPrice = (mutationList, observer) => {
							for (const mutation of mutationList) {

								if (mutation.type === "childList") {
									
									const mutations = [...mutation.addedNodes];						

									//console.log(mutation);
									mutations.forEach(node => {
										
										if(node.className === 'woocommerce-variation-price') {
									
											const price = node.querySelector('.amount');
											
											const quantity = document.querySelector('input[name="quantity"]').value;

											const splitPrice = price.innerHTML.split('').slice(1).join('');
											const calculatedPrice = parseFloat(splitPrice.replace(/,/g, ''));

											const totalPrice = Math.round(quantity * calculatedPrice * 100) / 100;

											const totalPriceCalculation = document.querySelector('.total-calculated-price');

											if(totalPriceCalculation) {

												totalPriceCalculation.textContent = totalPrice.toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + nett;
												
											} else {

												const totalPriceCalculationEl = document.createElement('p');
												totalPriceCalculationEl.classList.add('total-calculated-price');
												
												totalPriceCalculationEl.textContent = totalPrice.toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + nett;
												price.parentElement.after(totalPriceCalculationEl);

											}

											const perItemTextEl = document.createElement('p');
											perItemTextEl.textContent = ' per item';
											perItemTextEl.classList.add('per-flag-text');

											price.appendChild(perItemTextEl);

											// add total text
											const priceWrapper = document.querySelector('.woocommerce-variation-price .price')

											const totalText = document.createElement('p');
											totalText.textContent = 'total';
											totalText.classList.add('total-text');

											//priceWrapper.insertBefore(totalText);
											priceWrapper.insertAdjacentElement('afterbegin', totalText);
											
										}

									})

									//console.log(`The new list has ${mutation.addedNodes[0].children[0].childElementCount} chilren.`);
								}
							}
						};


						// Create an observer instance linked to the callback function
						const createTotalPriceObserver = new MutationObserver(createTotalPrice);

						// Start observing the target node for configured mutations
						createTotalPriceObserver.observe(variableProductPrice, config);


						//
						// Add event for the quantity change
						//															

						const quantityElement = document.querySelector('input[name="quantity"]');

						const updateTotalValue = (e) => {
							
							const totalPriceEl = document.querySelector('.total-calculated-price');

							if(!totalPriceEl) return;
							
							const quantity = e.currentTarget.value;
							
							setTimeout(() => {
								const price = document.querySelector('.single_variation_wrap .amount');
								const splitPrice = price.innerHTML.split('').slice(1).join('');
								const calculatedPrice = parseFloat(splitPrice.replace(/,/g, ''));

								const totalPrice = Math.round(quantity * calculatedPrice * 100) / 100;

								totalPriceEl.textContent = totalPrice.toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + nett;
							}, 100)	

						}

						quantityElement.addEventListener('change', updateTotalValue);
						
					} else if (gravityProductPrice) {

                        // Options for the observer (which mutations to observe)
						const config = { attributes: false, childList: true, subtree: true };

                        // Callback function to execute when mutations are observed
                        const createTotalPrice = (mutationList, observer) => {
                            for (const mutation of mutationList) {

                                if (mutation.type === "childList") {

                                    const priceEl = mutation.target;
                                    if(!priceEl.classList.contains('formattedTotalPrice')) return;
                                    const price = priceEl.textContent;

                                    const quantity = document.querySelector('input[name="quantity"]').value;

                                    const splitPrice = price.split('').slice(1).join('');
                                    const calculatedPrice = parseFloat(splitPrice.replace(/,/g, ''));

									const totalPrice = Math.round(quantity * calculatedPrice * 100) / 100;

                                    const totalPriceCalculation = document.querySelector('.total-calculated-price');

                                    if(totalPriceCalculation) {

                                        totalPriceCalculation.textContent = totalPrice.toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                                    } else {

                                        const totalPriceCalculationEl = document.createElement('p');
                                        totalPriceCalculationEl.classList.add('total-calculated-price');
                                        totalPriceCalculationEl.textContent = totalPrice.toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                        priceEl.after(totalPriceCalculationEl);

                                    }

                                }
                            }
                        };


                        // Create an observer instance linked to the callback function
                        const createTotalPriceObserver = new MutationObserver(createTotalPrice);

                        // Start observing the target node for configured mutations
                        createTotalPriceObserver.observe(gravityProductPrice, config);

                        //
						// Add event for the quantity change
						//															

						const quantityElement = document.querySelector('input[name="quantity"]');

                        const updateTotalValue = (e) => {

                            const totalPriceEl = document.querySelector('.total-calculated-price');

                            if(!totalPriceEl) return;

                            const price = gravityProductPrice;
                            const quantity = e.currentTarget.value;

                            const splitPrice = price.innerHTML.split('').slice(1).join('');
                            const calculatedPrice = parseFloat(splitPrice.replace(/,/g, ''));

							const totalPrice = Math.round(quantity * calculatedPrice * 100) / 100;

                            totalPriceEl.textContent = totalPrice.toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                        }

                        quantityElement.addEventListener('change', updateTotalValue);

                    } else if(simpleProductPrice) {
						
						//Move description above price
						const description = document.querySelector('.woocommerce-product-details__short-description');
						const productTitle = document.querySelector('.product_title');
						if(description) {
							productTitle.after(description)
						}

                        const totalSimplePriceEl = document.createElement('span');
                        totalSimplePriceEl.classList.add('total-calculated-price');
                        totalSimplePriceEl.textContent = simpleProductPrice.querySelector('.amount').textContent;
                        simpleProductPrice.after(totalSimplePriceEl);

                        simpleProductPrice.querySelector('.amount').textContent = simpleProductPrice.querySelector('.amount').textContent + " per item";

                        //
						// Add event for the quantity change
						//															

						const quantityElement = document.querySelector('input[name="quantity"]');

                        const updateTotalValue = (e) => {

                            const totalPriceEl = document.querySelector('.total-calculated-price');

                            if(!totalPriceEl) return;

                            const price = simpleProductPrice.querySelector('.amount');
                            const quantity = e.currentTarget.value;

                            const splitPrice = price.innerHTML.split('').slice(1).join('');
                          	const calculatedPrice = parseFloat(splitPrice.replace(/,/g, ''));

							const totalPrice = Math.round(quantity * calculatedPrice * 100) / 100;
							
                            totalPriceEl.innerHTML = "&pound;" + totalPrice.toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                        }

                        quantityElement.addEventListener('change', updateTotalValue);

                    } 
                    

				})
				
			}

        </script>
       
    <?php endif;

}
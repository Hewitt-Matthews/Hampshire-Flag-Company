<?php

add_action( 'wp_footer', 'variable_product_discounts' );

function variable_product_discounts() {
	
	global $product;
	
	require get_stylesheet_directory() . '/includes/shop-functions/discounts-array.php';
	$current_product_id = get_queried_object_id();
	$product_category_slugs = wp_get_post_terms($current_product_id, 'product_cat', array('fields' => 'slugs'));
	$has_discount_table = false;
	$discount_json = null;
	
	foreach ($product_category_slugs as $product_category_slug) {
			
		if (isset($discount_array['categories'][$product_category_slug])) {
			$has_discount_table = true;
			$discount_json = json_encode($discount_array['categories'][$product_category_slug]);
		}
		
		if (isset($discount_array['products'][$current_product_id])) {
			$has_discount_table = true;
			$discount_json = json_encode($discount_array['products'][$current_product_id]);
		}

	}
	
	//Leave if there is no table
	if(!$has_discount_table || !$product->is_type( 'variable' )) return;
	 
	?>

        <script>
				
			window.addEventListener('load', () => {
				
				//Discount Object
				const productDiscounts = JSON.parse('<?= $discount_json; ?>');

				//
				//
				//Variable Product Price Logic
				//
				//
				let discount = null;

					//Get the default Quantity Field
					const defaultQuanityField = document.querySelector('input[name="quantity"]');
					const quantityMessageEl = document.createElement('div');
					quantityMessageEl.classList.add('add-more-message');
					defaultQuanityField.closest('.woocommerce-variation-add-to-cart').after(quantityMessageEl);
				
					let hasPriceBeenSet = false;
					let unitPrice = null;

					//Create an event listenter to update the value of the default quantity field when the custom one is changes
					defaultQuanityField.addEventListener('change', (e) => {

						const quantity = e.target.value;
			
						for (const range in productDiscounts) {
							const [min, max] = range.split(' - ');
							if(quantity < parseInt(min)) {
								discount = null;
								quantityMessageEl.textContent = `Add ${parseInt(min) - quantity} more to your basket to qualify for a ${productDiscounts[range]} discount to this item.`;
								break;
							}

							if (quantity >= parseInt(min) && quantity <= parseInt(max)) {
								discount = productDiscounts[range];

								if(discount == productDiscounts[Object.keys(productDiscounts)[Object.keys(productDiscounts).length - 1]]) {
									quantityMessageEl.textContent = `You're currently getting ${discount} off this item.`;
								} else {
									quantityMessageEl.textContent = `You're currently getting ${discount} off this item! Add ${(parseInt(max) + 1) - quantity} more to your basket to qualify for a further discount to this item.`;
								}

								break;
							}
						}

						//Update Price<span class="amount">£135.05 <span class="per-flag-text">per item</span></span>
						const unitPriceEl = document.querySelector('.woocommerce-variation-price .price .amount');
						

						if(unitPriceEl) {
							
							if(!hasPriceBeenSet) {
								const unitPriceValue = unitPriceEl.childNodes[0].nodeValue.trim();
								const splitValue = unitPriceValue.split('');
								splitValue.shift();
								unitPrice = parseFloat(splitValue.join(''));
								hasPriceBeenSet = true;
							}
							
							
							if(unitPrice) {					

								if(!discount) {

									unitPriceEl.innerHTML = `&pound;${unitPrice} <span class="per-flag-text">per item</span>`;

								} else {

									const currentDiscount = parseFloat(discount.replace("%", ""));
									const dicountAmount = (unitPrice / 100) * currentDiscount;

									const newUnitPrice = Math.ceil((unitPrice - dicountAmount) * 100) / 100;
									unitPriceEl.innerHTML = `&pound;${newUnitPrice} <span class="per-flag-text">per item</span>`;

								}

							}

						}
						
					})
					
					// Add event listeners to detect changes in the selection
					const swatchContainers = document.querySelectorAll('.tawcvs-swatches');

					swatchContainers.forEach((container) => {
					  const swatches = container.querySelectorAll('.swatch');

					  swatches.forEach((swatch) => {
						swatch.addEventListener('click', () => {
						  	hasPriceBeenSet = false;
						});
					  });
					});
					

					//Set Default Options
					defaultQuanityField.value = 1;
					defaultQuanityField.dispatchEvent(new Event('change'));

			})

        </script>
<?php
}
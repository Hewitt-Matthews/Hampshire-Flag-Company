<?php

add_action( 'wp_footer', 'custom_banners_scripts_and_styles' );

function custom_banners_scripts_and_styles() {
	
    $pageID = get_queried_object_id();

    if($pageID == 524948) : ?>

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
			.single-product .woocommerce-info {
				top: 80px;
			}
		</style>

        <script>
			if(document.body.classList.contains('postid-524948')) {
				window.addEventListener('load', () => {
					const productDiscounts = {
						"2 - 3": "2.5%",
						"4 - 5": "5%",
						"6 - 10": "7.5%",
						"11 - 20": "10%",
						"21 - 9999990": "12.5%"
					};
					
					let discount = null;
					const dynamicPriceEl = document.querySelector('.product_totals ul > li:last-of-type > div span');
					const perFlagTextEl = document.createElement('p');
					perFlagTextEl.textContent = ' per banner';
					perFlagTextEl.classList.add('per-flag-text');
					dynamicPriceEl.appendChild(perFlagTextEl);

					setTimeout(() => { 
						const clone = dynamicPriceEl.closest('li').cloneNode();
						clone.classList.add('visible-prices');
						const cloneTitle = document.createElement('label');
						cloneTitle.classList.add('gfield_label');
						cloneTitle.textContent = "Total";
						clone.appendChild(cloneTitle);
						
						const cloneUnitPrice = document.createElement('div');
						cloneUnitPrice.classList.add('unit-price');
						const unitPriceText = document.querySelector('.formattedTotalPrice').innerText;
						const unitPriceValue = parseFloat(unitPriceText.replace(/[^0-9.]/g, ''));
						cloneUnitPrice.textContent = unitPriceText;
						cloneUnitPrice.appendChild(perFlagTextEl);
						clone.appendChild(cloneUnitPrice);

						const calculatedPrice = parseFloat(unitPriceValue);
						const cloneTotalPrice = document.createElement('div');
						cloneTotalPrice.classList.add('total-price');
						cloneTotalPrice.textContent = calculatedPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
						clone.appendChild(cloneTotalPrice);

						dynamicPriceEl.closest('ul').appendChild(clone);
				
						const customQuanityField = document.querySelector('#field_27_39.gfield--type-number input');
						const quantityMessageEl = document.createElement('div');
						quantityMessageEl.classList.add('add-more-message');
						customQuanityField.after(quantityMessageEl);

						const defaultQuanityField = document.querySelector('input[name="quantity"]');
						defaultQuanityField.parentElement.setAttribute('style', 'display: none');

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

							const unitPrice = document.querySelector('.formattedTotalPrice');
							const unitPriceText = unitPrice.innerText;
							const unitPriceValue = parseFloat(unitPriceText.replace(/[^0-9.]/g, ''));
							const unitPriceEl = document.querySelector('.unit-price');
							const totalPriceEl = document.querySelector('.total-price');
							let totalPrice;

							if(!discount) {
								const unitPrice = unitPriceValue;
								unitPriceEl.innerHTML = `&pound;${unitPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <span class="per-flag-text"> per flag</span>`;
								totalPrice = (customQuanityField.value * unitPriceValue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
								totalPriceEl.textContent = totalPrice;
							} else {
								const currentDiscount = parseFloat(discount.replace("%", ""));
								const discountAmount = (unitPriceValue / 100) * currentDiscount;
								const unitPrice = Math.ceil((unitPriceValue - discountAmount) * 100) / 100;
								unitPriceEl.innerHTML = `&pound;${unitPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <span class="per-flag-text"> per flag</span>`;
								totalPrice = (customQuanityField.value * unitPrice).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
								totalPriceEl.textContent = totalPrice;
							}

							const observer = new MutationObserver(() => {
								const unitPriceText = unitPrice.innerText;
								const currentUnitPrice = parseFloat(unitPriceText.replace(/[^0-9.]/g, ''));
								let totalPrice;

								if(!discount) {
									const unitPrice = currentUnitPrice;
									unitPriceEl.innerHTML = `&pound;${unitPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <span class="per-flag-text"> per flag</span>`;
									totalPrice = (customQuanityField.value * currentUnitPrice).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
									totalPriceEl.textContent = totalPrice;
								} else {
									const currentDiscount = parseFloat(discount.replace("%", ""));
									const discountAmount = (currentUnitPrice / 100) * currentDiscount;
									const unitPrice = Math.ceil((currentUnitPrice - discountAmount) * 100) / 100;
									unitPriceEl.innerHTML = `&pound;${unitPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <span class="per-flag-text"> per flag</span>`;
									totalPrice = (customQuanityField.value * unitPrice).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
									totalPriceEl.textContent = totalPrice;
								}
								
								const buyButton = document.querySelector('button.single_add_to_cart_button');
								const minimumPrice = 0;

								if(totalPrice > minimumPrice) {
									const buttonMessage = document.querySelector('.valid-selection-message');
									if(buttonMessage) buttonMessage.remove();
								} else {
									const buttonMessage = document.querySelector('.valid-selection-message');
									if(!buttonMessage) {
										//const buttonMessageEl = document.createElement('p');
										//buttonMessageEl.classList.add('valid-selection-message');
										//buttonMessageEl.innerHTML = `Please note this product has a minimum purchase price of &pound;${minimumPrice}. As your current total is below &pound;${minimumPrice}, we'll round that up in the checkout process.`;
										buyButton.after(buttonMessageEl)
									}
								}
							});

							observer.observe(unitPrice, { childList: true, subtree: true });
						})

						customQuanityField.value = 1;
						customQuanityField.setAttribute('min', '0');
						customQuanityField.dispatchEvent(new Event('change'));
					}, 1000);
				})
			}
        </script>

    <?php endif;
}
?>
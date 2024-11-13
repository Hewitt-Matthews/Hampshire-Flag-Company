<?php 

add_action('wp_footer', 'custom_attribute_based_quantity_script');

function custom_attribute_based_quantity_script() {
    // Only run this on the single product page
    if (!is_product()) {
        return;
    }

    global $product;

    // Check if the product belongs to category 568
    if ( ! has_term( 568, 'product_cat', $product->get_id() ) ) {
        return;
    }
    
    ?>
   	<script type='text/javascript'>
    window.onload = function(){
        jQuery(document).ready(function($){
			
			let originalPricePerItem;

			function getOriginalPrice(selector) {
				// Get the price string
				var priceString = $(selector).text();

				// Remove any non-numeric characters (excluding the decimal point)
				priceString = priceString.replace(/[^\d.]/g, '');

				// Parse and return the price
				return parseFloat(priceString);
			}

            function updatePrices(quantity, eventType){
				
				if(eventType != 'quantity' || !originalPricePerItem) {
					originalPricePerItem = getOriginalPrice('.woocommerce-variation-price .price .amount');
				} 
				const newPricePerItem = originalPricePerItem / 250;
 				const newTotalPrice = newPricePerItem * quantity;
				

				setTimeout(() => {
					$('.woocommerce-variation-price .price .amount').text('£' + newPricePerItem.toFixed(2) + ' per item');
					
					if(eventType != 'quantity' && newTotalPrice > newPricePerItem) {
						$('.woocommerce-variation-price .price .total-calculated-price').text(newTotalPrice.toFixed(2));
					}
					
				}, 0)

            }

            function checkAndUpdateQuantity(eventType){
				
				// Use the function to get the original prices
				if(!originalPricePerItem) {
					originalPricePerItem = getOriginalPrice('.woocommerce-variation-price .price .amount');
				}
				
				// Ensure the prices have been properly retrieved
                if(isNaN(originalPricePerItem)){
                    return;
				}
				
                var packQuantity = $('.tawcvs-swatches[data-attribute_name=\"attribute_pa_pack-quantity\"] .swatch.selected').attr('data-value'); 
                var flagMaterial = $('.tawcvs-swatches[data-attribute_name=\"attribute_pa_flag-material\"] .swatch.selected').attr('data-value');
                var stickType = $('.tawcvs-swatches[data-attribute_name=\"attribute_pa_stick-type\"] .swatch.selected').attr('data-value');
                var quantity = parseInt($('.quantity .qty').val());

                // If all attributes are selected
                if(packQuantity && flagMaterial && stickType){
                    // If the specific combination is selected
                    if(packQuantity == '250' && flagMaterial == 'paper' && stickType == 'biodegradable-paper'){
                        // Set the minimum value to 250
                        $('.quantity .qty').attr('min', '250');
						if($('.quantity .qty').val() < 250) {
							$('.quantity .qty').val('250');
						}

                        // Update the prices
                        updatePrices(quantity, eventType);
                    } else {
                        // Otherwise, set the minimum value to 1
                        $('.quantity .qty').attr('min', '1');
                    }
                } else {
					if($('.quantity .qty').val() >= 250) {
						$('.quantity .qty').val('1');
					}
					$('.quantity .qty').attr('min', '1');
				}
            }

           // Listen to the click event of your swatch items
			$('.tawcvs-swatches .swatch').on('click', function() {
				// Need to use a timeout due to the way the plugin works
				setTimeout(function() {
					checkAndUpdateQuantity('swatch');
				}, 0);
			});

			// Listen to the input event of the quantity input field
			$('.quantity .qty').on('input', function() {
				setTimeout(function() {
					checkAndUpdateQuantity('quantity');
				}, 0);
			});

            // Initial check
            checkAndUpdateQuantity();

        });
    }
    </script>
    <?php ;
}
add_action('woocommerce_before_calculate_totals', 'custom_price_based_on_qty', 10, 1);
function custom_price_based_on_qty($cart_object) {

    if (is_admin() && ! defined('DOING_AJAX')) return;

    foreach ($cart_object->get_cart() as $key => $value ) {
        // Get the product
        $product = $value['data'];

        // Only proceed if it's a variable product and has the specific term
        if($product->is_type('variation') && has_term(568, 'product_cat', $product->get_parent_id())) {

            $attributes = $product->get_variation_attributes();

            // Check if the attributes match your criteria
            if (isset($attributes['attribute_pa_flag-material']) && $attributes['attribute_pa_flag-material'] === 'paper'
                && isset($attributes['attribute_pa_stick-type']) && $attributes['attribute_pa_stick-type'] === 'biodegradable-paper'
                && isset($attributes['attribute_pa_pack-quantity']) && $attributes['attribute_pa_pack-quantity'] === '250') {

                // Get the quantity
                $qty = $value['quantity'];

                // Calculate the new price
                $new_price = ($product->get_price() / 250);

                // Set the new price
                $product->set_price($new_price);
            }
        }
    }
}



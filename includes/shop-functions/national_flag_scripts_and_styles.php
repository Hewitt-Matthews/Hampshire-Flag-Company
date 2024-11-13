<?php

function national_flag_scripts() {
	
	?>

        <script>
			
			window.addEventListener('load', () => {
				
				const formLabels = document.querySelectorAll('.product form .gfield_label');
				formLabels.forEach(label => {
					
					label.parentElement.setAttribute('data-field', label.textContent);
					
				})
				
				//Code to set the preselected options
				const materialOption = document.querySelector('.ginput_container.ginput_container_radio .gfield_radio > div:first-of-type input');
				if(materialOption) materialOption.click();
				
				const sizeOption = document.querySelector('fieldset.size-option[data-conditional-logic="visible"] .gchoice:nth-child(8) input');
				if(sizeOption) sizeOption.click();
				
				const shapeOption = document.querySelector('fieldset[data-conditional-logic="visible"][data-field="Shape(Required)"] .gfield_radio > div:first-of-type input');
				if(shapeOption) shapeOption.click();
				
				const finishingOption = document.querySelector('fieldset[data-conditional-logic="visible"][data-field="Finishing(Required)"] .gfield_radio > div:first-of-type input');
				if(finishingOption) finishingOption.click();
				
				const frayOption = document.querySelector('fieldset[data-conditional-logic="visible"][data-field="Add anti-fray netting?(Required)"] .gfield_radio > div:last-of-type input');
				if(frayOption) frayOption.click();
				
				
				//Add event listener to materials so that the previously selected size is re-selected
				const materialOptions = document.querySelectorAll('fieldset[data-field="Material(Required)"] input');
				const sizeOptions = document.querySelectorAll('fieldset.size-option input');

				const setSizeSelection = (e) => {
					
					let previouslySelectedSize = document.querySelector('fieldset.size-option input:checked');

					setTimeout(() => {

						if(!previouslySelectedSize) return;

						previouslySelectedSize = previouslySelectedSize.value.replace(/\|[\d.]+$/, '');

						// Reset checked status for all size options
						//sizeOptions.forEach(sizeOption => sizeOption.checked = false);

						let newSizes = document.querySelectorAll(`fieldset.size-option input[value^="${previouslySelectedSize}"]`);

						console.log(previouslySelectedSize);
						console.log(newSizes);
						
						newSizes.forEach(size => {
							
							//if(!size.disabled) size.checked = true;
							if(!size.disabled) size.click();
							
						})

// 						if(newSize) {
// 							//newSize.checked = true;
// 						}

					}, 100); // adjust delay as needed, this waits for 100ms

				}

				materialOptions.forEach(material => {
					material.addEventListener('click', setSizeSelection)
				});



			})

        </script>

	<?php
	
}


function national_flag_scripts_init() {
	
	$category_id = get_queried_object_id();
	$categories_assigned_to_product = get_the_terms( $category_id, 'product_cat' );
	$cat_ids = [];
	if ( ! empty( $categories_assigned_to_product ) ) {
		foreach ( $categories_assigned_to_product as $term ) {
			array_push($cat_ids, $term->term_id);
		}
	}
	// Get the parent category ID you want to check against
	$parent_category_id = 24;

	// Get an array of IDs for all the child categories of the parent category
	$child_category_ids = get_term_children( $parent_category_id, 'product_cat' );
	
	$common_cats = array_intersect($cat_ids, $child_category_ids);

	// Check if the current category is a child of the parent category
	if ( has_term( $parent_category_id, 'product_cat', $category_id ) || $common_cats ) {
		add_action( 'wp_footer', 'national_flag_scripts' );
	} 
	
}
add_action( 'woocommerce_before_single_product', 'national_flag_scripts_init' );
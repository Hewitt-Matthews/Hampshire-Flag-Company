const productInit = () => {
	
	//
	//
	//Bulk Pricing Table Fix
	//
	//

 	const tableParent =  document.querySelector('.awdr-bulk-customizable-table');
	
	if(tableParent) {
		const actualTable = tableParent.children[0];
		tableParent.after(actualTable);
		tableParent.remove();
	}
	

	
	//
	//
	//Product Summary Toggles
	//
	//
	
	const productSummaryToggles = document.querySelectorAll('.product-after-element>h2');
	
	const toggleSummary = (e) => {
		
		//Toggle Active Class of H2
		e.target.classList.toggle('active');
		
		//Get Height of content and toggle the accordion
		const summaryContent = e.target.nextElementSibling;	
		const summaryStyles = window.getComputedStyle(summaryContent);
		const summaryHeight = summaryStyles.getPropertyValue('max-height');

		if(summaryHeight == "0px") {
			summaryContent.setAttribute('style', `max-height: ${summaryContent.scrollHeight}px;`);
		} else {
			summaryContent.removeAttribute('style');
		}
		
	}
	
	productSummaryToggles.forEach(toggle => {
		//console.log(toggle);
		toggle.addEventListener('click', toggleSummary);
	})
	
	//
	//
	//Sticky Gallery Height Adjustment
	//
	//
	
	const flexViewportGalleryParent = document.querySelector('.flex-viewport');
	
	if(flexViewportGalleryParent) {
		
		const galleryStickyOffset = flexViewportGalleryParent.clientHeight;
		flexViewportGalleryParent.nextElementSibling.setAttribute('style', `top: ${galleryStickyOffset + 70}px;`)
	
	}
	
	//
	//
	//National Flag Add On toggles
	//
	//
	
	const initNationalFlagPage = () => {
		
		if( !document.querySelector('.wc-pao-addons-container')) return;
		
		const inputsToChecked = [
			"Printed",
			"1/2 Yard (45cm x 22cm)",
			"Rectangular",
			"Rope and Toggle",
			"No"
		];
		
		inputsToChecked.forEach(inputLabel => {
			
			const input = document.querySelector(`.wc-pao-addons-container input[data-label="${inputLabel}"]`);
			if(input) input.click();
			
		})
		
		const sizeAddonContainerLabels = document.querySelectorAll('.wc-pao-addon-container:not(.wc-pao-required-addon) label');
		
		sizeAddonContainerLabels.forEach(label => {
			
			if(label.outerText.trim() == "None") label.parentElement.remove();
			
		})
		
		// 
		// 
		// 
		// Novelty Flag Selection
		// 
		// 
		// 
		
		const noveltyFlagToggle = document.querySelector('.wc-pao-addons-container input[data-label="Novelty"]');
		
		if(noveltyFlagToggle) {
							
			noveltyFlagToggle.addEventListener('click', () => {

				const noveltyOptions = document.querySelectorAll('.wc-pao-addon-container:nth-child(5), .wc-pao-addon-container:nth-child(7), .wc-pao-addon-container:nth-child(9), .wc-pao-addon-container:nth-child(11)');

				noveltyOptions.forEach(option => {	

						const input = option.querySelector('input');
						input.click();

				})

			})
			
		}
		
		
		// Select the node that will be observed for mutations
		const targetNode = document.querySelector('#product-addons-total');

		// Options for the observer (which mutations to observe)
		const config = { attributes: false, childList: true, subtree: true };

		// Callback function to execute when mutations are observed
		const callback = (mutationList, observer) => {
			for (const mutation of mutationList) {
				if (mutation.type === "childList") {
					
					console.log()
					
					const buyButton = document.querySelector('button.single_add_to_cart_button');
					const numberOfListItems = mutation.addedNodes[0].children[0].childElementCount;
					const requiredListItems = document.querySelectorAll('.wc-pao-addon-container:not(.wc-pao-addon-container[style="display: none;"])').length + 2;
					
					console.log(`${requiredListItems} vs ${numberOfListItems}`)
					
					if(numberOfListItems === requiredListItems) {
						
						const buttonMessage = document.querySelector('.valid-selection-message');
						if(buttonMessage) buttonMessage.remove();
						
					   	buyButton.disabled = false;
					} else {
					
						const buttonMessage = document.querySelector('.valid-selection-message');
						
						if(!buttonMessage) {
							const buttonMessageEl = document.createElement('p');
							buttonMessageEl.classList.add('valid-selection-message');
							buttonMessageEl.textContent = 'Please make a valid selection before purchase';
							buyButton.after(buttonMessageEl)
						}
					
						buyButton.disabled = true;
					}
				
					//console.log(`The new list has ${mutation.addedNodes[0].children[0].childElementCount} chilren.`);
				}
			}
		};

		// Create an observer instance linked to the callback function
		const observer = new MutationObserver(callback);

		// Start observing the target node for configured mutations
		observer.observe(targetNode, config);
		
	}
	
	initNationalFlagPage();

	
}

window.addEventListener('load', productInit);
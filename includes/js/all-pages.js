const globalInit = () => {
	
	const basketIcon = document.querySelector('#site-header-cart');
	let isBasketOpen = false;
	
	const toggleBasket = (e) => {
		
		if(document.body.classList.contains('woocommerce-checkout')) return;
			
		e.preventDefault();
		
		//Page Elements
		const pageContainer = document.querySelector('#page #content');
	
		//Basket Elements
		const target = e.currentTarget;
		const basketSummary = document.querySelector('.widget.woocommerce.widget_shopping_cart');
		
		//Open Basket Function
		
		const openBasket = (e) => {
			
			isBasketOpen = true;
			
			let height = 0;
			
			const headerEls = document.querySelectorAll('#wpadminbar, #topbar, #topbar + header');
			headerEls.forEach(el => {
				
				height += el.getBoundingClientRect().height;
				
			})
			
			document.body.appendChild(basketSummary);
			basketSummary.setAttribute('style', `top: ${height}px;`);
			
			basketSummary.classList.add('active');
			pageContainer.classList.add('basket-open');
	
		}
		
		//Close Basket Function
		const closeBasket = (e) => {
			
			isBasketOpen = false;
			
			basketSummary.classList.remove('active');
			basketSummary.removeAttribute('style');
			pageContainer.classList.remove('basket-open');
			
			target.children[1].appendChild(basketSummary);
			
			const basketDupe = document.querySelector('body > .widget.woocommerce.widget_shopping_cart');
			
			if(basketDupe) basketDupe.remove();
			
		}
		
		if(!isBasketOpen) {
			
			openBasket();		
			pageContainer.addEventListener('click', closeBasket);
			
		} else {
			
			closeBasket();
			pageContainer.removeEventListener('click', closeBasket);
			
		}
		
	}
	
	basketIcon.addEventListener('click', toggleBasket);
	
	//Animate on scroll all rows
	const allSections = document.querySelectorAll('.hfc-row');
    
    allSections.forEach(section => {
        //section.setAttribute('data-aos', 'fade-up');
		//section.setAttribute('data-aos-anchor-placement', 'top-bottom');
		//section.setAttribute('data-aos-once', 'true');
    })
    
    //AOS.init();
    
	const loadingScreen = document.querySelector('#loading-screen');
	setTimeout(() => {
		loadingScreen.remove();
	}, 300)
	
}

window.addEventListener('load', globalInit);
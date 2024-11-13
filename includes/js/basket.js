const updateBasketHandwaverQuantity = () => {
	
	let isHandwaverInCart = false;
	
	const cartItems = document.querySelectorAll('tr.cart_item');
	const handwaverCartItems = [];
	
	cartItems.forEach(item => {
		
		const itemName = item.querySelector('.product-name > a').textContent;
		
		if(itemName === "Standard Size Custom Design Handwaving Flags") {
			
			if(!isHandwaverInCart) isHandwaverInCart = true;
		
			handwaverCartItems.push(item);
		
		}
		
	})
	
	if(!isHandwaverInCart) return;
	
	handwaverCartItems.forEach(handwaver => {
		
		let isPaperStickPaperFlag = false;
		
		const stickType = handwaver.querySelector('.variation dd.variation-HandwaverType p').textContent;
		
		if(stickType === "Paper Handwaver / Paper Stick") isPaperStickPaperFlag = true;
		
		const quantityField = handwaver.querySelector('.quantity input');
		
		if(isPaperStickPaperFlag) {
			quantityField.min = 250;
		} else {
			quantityField.min = 10;
			quantityField.step = 10;
		}
		
	})
	
}


window.addEventListener('load', () => {
	
	updateBasketHandwaverQuantity();
	
})
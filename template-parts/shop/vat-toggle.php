
Vat nearly working 




<?php

$tax_rate = 20;

?>

<style> 
    
    .vat-container {
        display: flex;
        align-items: center;
        gap: 0.5em;
        position: fixed;
        right: 1rem;
        top: 6rem;
        z-index: 999;

        /* Temp */
        /* display: none !important; */
    }

    .vat-amount {
        color: grey;
        font-size: 14px;
        margin-left: 0.5em; /* Adjust as needed */
        display: inline; /* Default to inline */
    }

    .vat-amount.vat-amount--fixed {
        margin-left: 10px;
    }
    
    .vat-container p {
        margin: 0;
        text-transform: uppercase;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 1px;
    }

    .vat-toggle {
        display: flex;
        align-items: center;
        --padding: 3px;
        --toggleHeight: 26px;
        width: calc(var(--toggleHeight) * 2);
        height: var(--toggleHeight);
        background-color: #ccc;
        border-radius: 50px;
        padding: var(--padding);
        transition: 200ms;
    }
    
    .vat-toggle:hover {
        cursor: pointer;
    }
    
    .vat-container.wholesale .vat-toggle:hover {
        cursor: default;
    }
    
    .vat-toggle .switch {
        --switchSize: calc(var(--toggleHeight) - calc(var(--padding) * 2));
        width: var(--switchSize);
        height: var(--switchSize);
        border-radius: 50%;
        background-color: #fff;
        transition: 200ms;
    }
    
    .vat-toggle.active {
        background-color: rgb(var(--secondary));
    }
    
    .vat-toggle.active .switch {
        transform: translateX(calc(var(--toggleHeight)));
    }
    
    .vat-container .wholesaler-cta {
        display: none;
    }

    body>.wholesaler-cta {
        display: grid;
        position: fixed;
        z-index: 99999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgb(0 0 0 / 30%);
        place-content: center;
    }

    body>.wholesaler-cta .content {
        width: min(80vw, 600px);
        background-color: rgb(255 255 255);
        border-radius: 10px;
        position: relative;
        text-align: center;
        padding: 2em;
    }

    body>.wholesaler-cta .content a {
        display: inline-block;
        color: #fff;
        background-color: rgb(var(--secondary));
        padding: 0.9rem 1.5rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
        line-height: 1;
    }

    body>.wholesaler-cta .content span {
        position: absolute;
        top: 10px;
        right: 15px;
        font-weight: 100;
        font-size: min(40px, 10vw);
        line-height: 0.5;
    }

    body>.wholesaler-cta .content span:hover {
        cursor: pointer;
    }

    @media (max-width: 767px) { /* Change the max-width to 767px */
        .vat-amount {
            display: block; /* Change to block on mobile */
            margin-left: 0; /* Remove left margin for block display */
            margin-top: 0.5em; /* Add some top margin for spacing */
        }
    }

</style>

<div class="vat-container">

    <p>Including VAT</p>

    <div class="vat-toggle">

        <div class="switch"></div>

    </div>

</div>

<script>

window.addEventListener('load', () => {

    const vatContainer = document.querySelector('.vat-container');
    const vatContainerText = document.querySelector('.vat-container p');
    const vatToggle = document.querySelector('.vat-container .vat-toggle');

    const amount = document.querySelector('.starting-from .amount');
    

    let hasUpdatedPrices = false; // Flag to track if prices have been updated

    const originalPrice = parseFloat(document.querySelector('.starting-from .amount').innerText.replace(/[^0-9.]/g, '')); // Store the original price with VAT included

    vatToggle.addEventListener('click', () => {
        
        if(vatToggle.classList.contains('active')) {
            vatToggle.classList.remove('active');
            vatContainerText.innerText = 'Including VAT'; // Update text to Including VAT
            
            // Update display to show original price with VAT
            updateDisplay(true); // Pass true to indicate including VAT
        } else {
            vatToggle.classList.add('active');
            vatContainerText.innerText = 'Excluding VAT'; // Update text to Excluding VAT

            // Update display to show price without VAT
            updateDisplay(false); // Pass false to indicate excluding VAT
        }
    });

    // Add MutationObserver to watch for changes in the .cart form
    const cartForm = document.querySelector('form.cart');
    if (cartForm) {
        let isUpdating = false;

        const observer = new MutationObserver(() => {
            if (vatToggle.classList.contains('active') && !isUpdating) {
                isUpdating = true;
                updatePrices();
                setTimeout(() => {
                    isUpdating = false;
                }, 100);
            }
        });

        observer.observe(cartForm, {
            childList: true,
            subtree: true,
            attributes: true,
            characterData: true
        });
    }

    function updateDisplay(includeVAT) {
        const amount = document.querySelector('.starting-from .amount');
        const unitPrice = document.querySelector('.unit-price');
        const totalPrice = document.querySelector('.total-calculated-price'); // Targeting the total price
        const formattedTotalPrice = document.querySelector('.formattedTotalPrice.ginput_total'); // Targeting the formatted total price

        // Update display text for starting price
        if (amount) {
            updateDisplayText(amount, includeVAT);
        }
        // Update display text for unit price
        if (unitPrice) {
            updateDisplayText(unitPrice, includeVAT);
        }
        // Update display text for total price
        if (totalPrice) {
            updateDisplayText(totalPrice, includeVAT); // Update total price display
        }
        // Update formatted total price
        if (formattedTotalPrice) {
            updateDisplayText(formattedTotalPrice, includeVAT); // Update formatted total price display
        }

        // Update subtotal and options prices
        const subtotal = document.querySelector('.formattedBasePrice');
        const options = document.querySelector('.formattedVariationTotal');

        if (subtotal) {
            updateDisplayText(subtotal, includeVAT); // Update subtotal display
        }
        if (options) {
            updateDisplayText(options, includeVAT); // Update options display
        }
    }

    function updateDisplayText(element, includeVAT) {
        let displayPrice;
        const vatAmount = (originalPrice - (originalPrice / (1 + <?php echo $tax_rate / 100; ?>))).toFixed(2); // Calculate VAT amount

        // Calculate display price based on whether VAT is included or excluded
        if (includeVAT) {
            displayPrice = (originalPrice).toFixed(2); // Show original price including VAT
        } else {
            displayPrice = (originalPrice / (1 + <?php echo $tax_rate / 100; ?>)).toFixed(2); // Show price excluding VAT
        }

        // Update the element's text to the display price with the currency symbol
        element.innerText = `£${displayPrice}`;
    }
});

</script>
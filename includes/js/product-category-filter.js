jQuery(document).ready(function ($) {
    $('.wc-custom-filter-link').on('click', function (e) {
        e.preventDefault();
        var letter = $(this).data('filter');
		var slug = $(this).data('slug');
		
        $.ajax({
            url: wc_ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'wc_custom_filter_products',
                letter: letter,
				slug: slug
            },
            beforeSend: function () {
                $('.woocommerce').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function (response) {
                $('.woocommerce ul.products').html(response);
				document.querySelector('.content-area').classList.add('sorted-by-letter');
				
				if(document.querySelector('.letter-sorting-heading')) document.querySelector('.letter-sorting-heading').remove();
				
				const subheading = document.createElement('h2');
				subheading.classList.add('letter-sorting-heading');
				if(letter == "Show All") {
					subheading.textContent = `Showing all ${slug.replace('-', ' ')}`;
				} else {
					subheading.textContent = `${slug.replace('-', ' ')} sorted by the letter ${letter}`;
				}
				document.querySelector('.storefront-sorting').after(subheading);
				
                $('.woocommerce').unblock();
            }
        });
    });
});

<?php


//Build Shortcode to call in VAT toggle
add_shortcode( 'vatToggle', 'vat_toggle' );
function vat_toggle( ) {
	
	ob_start();

	get_template_part('template-parts/shop/vat-toggle'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to call in Services Grid
add_shortcode( 'servicesGrid', 'services_grid' );
function services_grid( ) {
	
	ob_start();

	get_template_part('template-parts/services/services-grid'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to call in Categories for Articles Page
add_shortcode( 'articleCategories', 'article_categories' );
function article_categories( ) {
	
	ob_start();

	get_template_part('template-parts/knowledge-base/articles/content', 'categories'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to call in Articles for Articles Page
add_shortcode( 'articleGrid', 'article_grid' );
function article_grid( ) {
	
	ob_start();

	get_template_part('template-parts/knowledge-base/articles/content', 'grid'); 
	
    return ob_get_clean();
	
}


//Build Shortcode to call in Contact info for Contact page
add_shortcode( 'contactInformation', 'contact_information' );
function contact_information( ) {
	
	ob_start();

	get_template_part('template-parts/contact/address-and-hours'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to call in Phone and email for Contact page
add_shortcode( 'phoneAndEmail', 'phone_and_email' );
function phone_and_email( ) {
	
	ob_start();

	get_template_part('template-parts/contact/phone-and-email'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to loop through the sale products
add_shortcode( 'saleProducts', 'sale_products' );
function sale_products( ) {
	
	ob_start();

	get_template_part('template-parts/shop/sale-products-loop'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to loop through the company achievements
add_shortcode( 'companyAchievements', 'company_achievements' );
function company_achievements( ) {
	
	ob_start();

	get_template_part('template-parts/home/company-achievements'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to loop through the featured products
add_shortcode( 'featuredProducts', 'featured_products' );
function featured_products( ) {
	
	ob_start();

	get_template_part('template-parts/shop/featured-products-loop'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to output the search bar
add_shortcode( 'searchBar', 'search_bar' );
function search_bar( ) {
	
	ob_start();

	get_template_part('template-parts/knowledge-base/search/bar'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to loop through the career benefits
add_shortcode( 'careerBenefits', 'career_benefits' );
function career_benefits( ) {
	
	ob_start();

	get_template_part('template-parts/careers/career-benefits'); 
	
    return ob_get_clean();
	
}


//Build Shortcode to loop through the career testimonials
add_shortcode( 'careerTestimonials', 'career_testimonials' );
function career_testimonials( ) {
	
	ob_start();

	get_template_part('template-parts/careers/career-testimonials'); 
	
    return ob_get_clean();
	
}



//Build Shortcode to list the service key services
add_shortcode( 'serviceKeyServicesList', 'key_services_list' );
function key_services_list( ) {
	
	ob_start();

	get_template_part('template-parts/services/key-services-list'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to output the service content
add_shortcode( 'serviceContent', 'service_content' );
function service_content( ) {
	
	ob_start();

	get_template_part('template-parts/services/single-service-content'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to output the work slider
add_shortcode( 'ourWorkSlider', 'our_work_slider' );
function our_work_slider( ) {
	
	ob_start();

	get_template_part('template-parts/services/our-work-slider'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to create post social share buttons
add_shortcode( 'socialShareButtons', 'social_share_buttons' );
function social_share_buttons(  ) {
	
	ob_start();

	get_template_part('template-parts/global-social-share'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to list popular posts
add_shortcode( 'popularPosts', 'popular_posts' );
function popular_posts(  ) {
	
	ob_start();

	get_template_part('template-parts/knowledge-base/articles/popular-posts'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to output the live chat
add_shortcode( 'liveChat', 'live_chat' );
function live_chat(  ) {
	
	ob_start();

	get_template_part('template-parts/global-chat'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to output the Charity Logos
add_shortcode( 'CharityLogos', 'charity_logos' );
function charity_logos(  ) {
	
	ob_start();

	get_template_part('template-parts/global-client-logos'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to output the Charity Logos
add_shortcode( 'CharityPageLogos', 'charity_page_logos' );
function charity_page_logos(  ) {
	
	ob_start();

	get_template_part('template-parts/global-charity-logos'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to output the author of a blog post
add_shortcode( 'blogAuthor', 'blog_author' );
function blog_author(  ) {
	
	ob_start();

	get_template_part('template-parts/knowledge-base/articles/content-author'); 
	
    return ob_get_clean();
	
}

//Build Shortcode to output video
add_shortcode( 'video', 'output_video' );
function output_video($atts) {
	
	ob_start();
	
	set_query_var('video_attributes', $atts); // Pass attributes to the template

	get_template_part('template-parts/video'); 
	
    return ob_get_clean();
	
}
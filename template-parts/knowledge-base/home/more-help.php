<?php

?>

<style>
	
	#need-help {
		background-color: #F4F4F4;
		text-align: center;
	}
	
	#need-help .hfc-row>p {
		max-width: 400px;
		margin: 0 auto 1em;
	}
	
	.contact-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
		gap: 2em;
		margin-top: 4em;
	}
	
	.contact-grid .contact-option {
		background-color: #fff;
		border-bottom: solid 3px rgb(var(--secondary));
		padding: 2em;
	}
	
	.contact-grid .contact-option .title {
		text-transform: uppercase;
		letter-spacing: 1px;
		font-weight: 500;
		font-size: 14px;
	}
	
	.contact-grid .email-option h3 {
		font-size: 20px;
	}
	
	.contact-grid .contact-option .img-container {
		background-color: rgb(var(--secondary));
		display: inline-flex;
		padding: 1em;
		border-radius: 50%;
		height: 75px;
		width: 75px;
		margin-bottom: 15px;
	}
	
	.contact-grid .contact-option .img-container img {
		filter: brightness(0) invert(1);
	}

</style>

<h2>Need more help?</h2>
<p>Speak to one of the team using one of the following methods or via the contact us page.</p>
<a href="/contact-us" class="primary-btn">Contact us</a>

<div class="contact-grid">
	
	<div class="contact-option">
		
		<p class="title">Phone</p>
		<div class="img-container">
			<img src="<?= get_stylesheet_directory_uri() . '/assets/call-icon.svg' ?>" alt="Phone Icon">
		</div>
		<h3>02392 237130</h3>
		<p>Mon - Fri. 8.30am - 5.30pm</p>
		
	</div>
	
	<div class="contact-option email-option">
		
		<p class="title">Email Us</p>
		<div class="img-container">
			<img src="<?= get_stylesheet_directory_uri() . '/assets/email-icon.svg' ?>" alt="Email Icon">
		</div>
		<h3>info@hampshireflag.co.uk</h3>
		<p>We will reply within one working day</p>
		
	</div>
	
	<div class="contact-option">
		
		<p class="title">Web Chat</p>
		<div class="img-container">
			<img src="<?= get_stylesheet_directory_uri() . '/assets/chat-icon.png' ?>" alt="Talk Icon">
		</div>
		<p>Chat to our team 24hours per day</p>
		<a href="javascript:void(Tawk_API.toggle())" class="primary-btn blue">Start chat</a>
		
	</div>
	
</div>
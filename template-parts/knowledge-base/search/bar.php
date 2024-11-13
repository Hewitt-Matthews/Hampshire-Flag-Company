<style>
	
	#search-container h2,
	.error404 #page #search-container h2 {
		text-align: center;
		margin-bottom: 1.5em;
	}
	
	#search-container form,
	.error404 #page #search-container form {
		padding: 5em;
		box-shadow: 0px 5px 10px 0px #bbb;
		background-color: #fff;
	}
	
	#search-container .input-container,
	.error404 #page #search-container .input-container {
		position: relative;
	}
	
	#search-container form input,
	.error404 #page #search-container form input {
    	border: solid 1px;
		border-radius: 50px;
		width: 100%;
		background-color: #fff;
		padding: 1em 2em;
	}
	
	#search-container form input::placeholder,
	.error404 #page #search-container form input::placeholder {
		text-transform: uppercase;
		letter-spacing: 1px;
		font-size: 14px;
		opacity: 0.5;
	}
	
	#search-container form button,
	.error404 #page #search-container form button {
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		right: 30px;
		background: none;
   		height: 80%;
    	padding: 0;
	}

</style>

<form action="<?= home_url('/') ?>" id="search-form" method="get">
    <div class="input-container">
        <input type="text" name="s" id="s" placeholder="How can we help" />
		<?php if(get_queried_object_id() == 8344) : ?>
			<input type="hidden" name="search_type" value="posts">
		<?php endif; ?>
        <button type="submit" value="Submit">
            <img class="search-icon" src="<?= get_stylesheet_directory_uri() . '/assets/search-icon.svg' ?>"/>
        </button>
    </div>
</form>


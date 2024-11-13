<?php 

global $post;
$permalink = get_permalink($post->ID);
$title = get_the_title();
$post_type = get_post_type();

if ( is_single() && !is_page() ) : ?>

<style>
	
	.share {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		gap: 2em;
		width: calc(100% - 10em);
		margin: 3em auto 0;
	}
	
	.share>span {
		text-transform: uppercase;
		font-weight: 700;
		letter-spacing: 1px!important;
	}

	@media only screen and (max-width: 980px) {
		
		.share {
			width: 100%;
			justify-content: flex-start;
		}
		
		.share>span {
			flex: 1 1 100%;
		}
		
		.share > a {
			flex: 0 1 30px;
		}
		
	}
	
	.share > a {
		padding: 0px!important;
		width: 100%;
		border: none!important;
	}
	
	.share > a::after {
		content: none!important;
	}
	
	.share > a > img {
		-webkit-filter: invert(100%);
    	filter: invert(100%);
	}
	
	.share > a:hover img {
		filter: drop-shadow(32px 0px 0px rgb(var(--primary)));
    	transform: translateX(-32px);
	}


</style>

<div class="share">
	
	<span>Share</span>
	
	<a target="_blank"  href="https://www.facebook.com/sharer/sharer.php?u=<?= $permalink ?>" onclick="window.open(this.href, \'facebook-share\',\'width=580,height=296\');return false;" class="et_pb_button"><img src="/wp-content/themes/hampshire-flag-company-1/assets/facebook-icon.svg"></a>
	
	<a target="_blank"  href="http://twitter.com/share?text=<?= $title ?>&url=<?= $permalink ?>" onclick="window.open(this.href, \'twitter-share\', \'width=550,height=235\');return false;" class="et_pb_button"><img src="/wp-content/themes/hampshire-flag-company-1/assets/twitter-icon.svg"></a>
				
	<a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $permalink ?>" onclick="window.open(this.href, \'linkedin-share\', \'width=490,height=530\');return false;" class="et_pb_button"><img src="/wp-content/themes/hampshire-flag-company-1/assets/linkedin-icon.svg"></a>
	
</div>

<?php endif; ?>
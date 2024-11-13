<?php

$post_id = get_the_ID();
$author_id = get_post_field ( 'post_author', $post_id );
$display_name = get_the_author_meta( 'display_name', $author_id );
$profile_image = get_the_author_meta( 'profile_photo', $author_id );

?>

<style>
	
	.et-db #et-boc .et-l .author-container img {
		max-width: 100px;
	}
	
	.et-db #et-boc .et-l .author-container h2 {
		text-transform: uppercase;
		letter-spacing: 1px;
		font-weight: 600;
	}
	
	.author-container .meta {
		display: flex;
		flex-wrap: wrap;
		gap: 1em;
		align-items: center;
	}
	
	.author-container .meta p {
		font-weight: 500;
	}

</style>


<div class="author-container">
	
	<h2>Author</h2>
	
	<div class="meta">
		
		<?= wp_get_attachment_image( $profile_image, "large", true, array( "loading" => "lazy" ) ); ?>
		<p><?= $display_name ?></p>
	
	</div>
	
</div>
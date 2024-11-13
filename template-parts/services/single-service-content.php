<style>
	
	.content-container {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(100%, 410px), 1fr));
		gap: 0 6em;
		grid-auto-rows: 1fr;
		grid-auto-flow: row dense;
	}
	
	.content-container .text-content h2 {
		text-transform: uppercase!important;
    	font-weight: 600!important;
    	letter-spacing: 1px!important;
	}

</style>

<?php

$content = get_field('service_content');

$image = $content['service_content_image'];
$text = $content['service_content_text'];
$button = $content['service_content_button'];

if ( $text ) : 

	$label = $button['button_label'];
	$link = $button['button_link'];

?>

	<div class="content-container">

		<?php if ( $image ) : 
		
			$size = 'large';
			$src = $image['sizes'][ $size ];
		
		?>
		
			<img src="<?= $src ?>" />
		
		<?php endif; ?>
		
		<div class="text-content">
			
			<?= $text ?>
			
			<?php if ( $label ) : ?>
			
				<a href="<?= $link ?>" class="primary-btn"><?= $label ?></a>
			
			<?php endif; ?>
			
		</div>

	</div>

<?php endif; ?>
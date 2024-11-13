<?php 

$templates = get_field('templates_and_guides');
$has_files = false;

if (isset($templates['files']) && is_array($templates['files'])) {
    foreach ($templates['files'] as $file) {
        if (!empty($file)) {
            $has_files = true;
            break;
        }
    }
}

$template_images = [
	"indesign" => '/wp-content/uploads/2023/01/indesign.svg',
	"pdf" => '/wp-content/uploads/2023/01/pdf.svg',
	"photoshop" => '/wp-content/uploads/2023/01/photoshop.svg',
	"illustrator" => '/wp-content/uploads/2023/01/illustrator.svg',
	"word" => '/wp-content/uploads/2023/01/instructions.svg',
	"video" => '/wp-content/uploads/2023/01/youtube.svg',
];

?>

<?php if($has_files) : ?>

<style>

	.templates_and_guides {
		background-color: #F8F9FA;
		padding: 3em 0;
		margin: 0 calc(calc(calc(100vw - min(1250px, 85vw)) / 2) / -1);
	}
	
	.templates_and_guides h2 {
		text-align: center;
		font-size: min(24px, 8vw);
		margin-bottom: 2em;
	}

	.templates_and_guides .guides {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(min(100%, 150px), 1fr));
		gap: 1em;
		max-width: var(--siteMaxWidth);
    	margin: 0 auto;
	}
	
	.templates_and_guides .guides a {
		text-align: center;
		text-transform: uppercase;
		letter-spacing: 1px;
		font-weight: 700;
	}
	
	.templates_and_guides .guides a:hover {
		text-decoration: underline;
	}
	
	.templates_and_guides .guides a img {
		max-width: 70px;
		margin: 0 auto 1em;
	}
	
	.templates_and_guides .guides h3 {
		font-size: min(18px, 8vw);
		margin-top: 1em;
	}
	
		
	.templates_and_guides.guides p {
		margin: 0;
	}

</style>

<div class="templates_and_guides">
	
	<h2>Artwork Templates & Instruction Guides</h2>
	
	<div class="guides">
		
		<?php foreach($templates['files'] as $file) :
			$template_type = $file['file_type'];
			$title = $file['title'];
			$url = $template_type == "video" ? $file['video_url'] : $file['file'];
		?>
		
			<?php if($url) : ?>
		
				<a href="<?= $url ?>" target="_blank">

					<?php foreach($template_images as $image_name => $image_path) : ?>

						<?php if($image_name == $template_type) : ?>

							<img src="<?= $image_path ?>" alt="Icon for <?= $template_type ?>">

						<?php endif; ?>

					<?php endforeach; ?>

					<p><?= $template_type ?></p>
					<h3><?= $title ?></h3>

				</a>

			<?php endif; ?>

		<?php endforeach; ?>
		
	</div>
	

</div>

<?php endif; ?>
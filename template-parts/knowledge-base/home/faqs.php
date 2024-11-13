<?php

$question_categories = get_terms( array( 
    'taxonomy' => 'question_categories',
    'parent'   => 0
) );

?>

<style>
	
	.faqs-container .question {
		border-bottom: solid 2px #E0E0E0;
   		margin-bottom: 2em;
	}
	
	.faqs-container .question h3 {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	
	.faqs-container .question h3 img {
		transform: rotate(-90deg);
		transition: 300ms;
	}
	
	.faqs-container .question h3 img:hover {
		cursor: pointer;
	}

	.faqs-container .question .answer {
		height: 0px;
		overflow: hidden;
		transition: 300ms;
		max-width: 900px;
	}

</style>

<div class="faqs-container">
	
	<?php foreach ($question_categories as $category) :
		
		$args = array(
			'post_type' => 'faqs',
			'tax_query' => array(
				array(
					'taxonomy' => 'question_categories',
					'field'    => 'slug',
					'terms'    => $category->slug,
				),
			),
		);
		$questions = new WP_Query( $args );
	
	?>
	
	<div>
	
		<h2><?= $category->name ?></h2>
	
		<?php if ( $questions->have_posts() ) : 
    		while ( $questions->have_posts() ) : $questions->the_post(); 
			$question_title = get_the_title();
			$question_answer = get_field('answer');
			?>
				
				<div class="question">
					
					<h3>
						<?= $question_title ?>
						<img class="faq-icon" src="<?= get_stylesheet_directory_uri() . '/assets/faq-icon.svg' ?>"/>
					</h3>
					<div class="answer">
						<?= $question_answer ?>
					</div>
					
				</div>
	
			<?php endwhile; 
		endif; ?>
	
	</div>

	<?php endforeach; ?>
	
</div>

<script>

const faqInit = () => {
	
	const faqItems = document.querySelectorAll('.faqs-container .question');
	
	faqItems.forEach(question => {
		
		const questionTitle = question.querySelector('h3');
		
		const toggleQuestion = (e) => {
		
			const h3 = e.target.nodeName == "H3" ? e.target : e.target.parentElement;
			const icon = h3.querySelector('img');
			
			const questionText = h3.nextElementSibling;
			const questionHeight = questionText.scrollHeight;
			
 			if(!questionText.clientHeight) {
				questionText.setAttribute('style', `height: ${questionHeight}px`);
				icon.setAttribute('style', 'transform: rotate(0deg);');
			} else {
				questionText.removeAttribute('style');
				icon.setAttribute('style', 'transform: rotate(-90deg);');
			}
	
			
		}
		
		questionTitle.addEventListener('click', toggleQuestion);
		
	})
	
}

window.addEventListener('load', faqInit);

</script>
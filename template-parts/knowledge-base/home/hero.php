<style>
	
	
	.knowledge-base-hero {
		background: rgb(var(--secondary));
		text-align: center;
		padding: 5em 0;
	}
	
	.knowledge-base-hero h1 {
		color: #fff;
		margin-bottom: 1em;
	}
	
	.knowledge-base-hero .category-list {
		display: flex;
		justify-content: space-around;
		max-width: calc(var(--siteMaxWidth) - 100px);
		margin: 0 auto;
		flex-wrap: wrap;
	}

	.knowledge-base-hero .category-list a {
		color: #fff;
		text-transform: uppercase;
		font-weight: 600;
		font-size: 15px;
		letter-spacing: 1px;
	}


</style>

<?php

$categories = get_categories( array(
    'orderby' => 'name',
    'parent'  => 0,
	'hide_empty' => true
) );
 
?>

<div class="knowledge-base-hero">
	
	<h1>Knowledge Base</h1>
	
	<div class="category-list">
		
		<?php foreach ( $categories as $category ) {
			
			printf( '<a href="%1$s">%2$s</a>',
				esc_url( get_category_link( $category->term_id ) ),
				esc_html( $category->name )
			);
	
		}

		?>
		
		<a href="/knowledge-base/articles">All Articles</a>
		
	</div>
	
</div>
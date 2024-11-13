<style>
	
	
	#page #primary .article-categories, #page #content .article-categories {
		padding: 2em 0;
	}
	
	#page #primary .article-categories .category-list, #page #content .article-categories .category-list {
		display: flex;
		justify-content: space-around;
		max-width: calc(var(--siteMaxWidth) - 100px);
		margin: 0 auto;
		flex-wrap: wrap;
	}

	#page #primary .article-categories .category-list a, #page #content .article-categories .category-list a {
		color: #000;
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

<div class="article-categories">
	
	<div class="category-list">
		
		<?php foreach ( $categories as $category ) {
			
			printf( '<a href="%1$s">%2$s</a>',
				esc_url( get_category_link( $category->term_id ) ),
				esc_html( $category->name )
			);
	
		}

		?>
		
	</div>
	
</div>
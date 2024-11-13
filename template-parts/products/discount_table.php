<?php

require get_stylesheet_directory() . '/includes/shop-functions/discounts-array.php';
$current_product_id = get_queried_object_id();
$product_category_slugs = wp_get_post_terms($current_product_id, 'product_cat', array('fields' => 'slugs'));
$category_slugs_intersect = array_intersect($product_category_slugs, array_keys($discount_array['categories']));

$is_table_added = false;

//echo '<pre>' . print_r($product_category_slugs, true) . '</pre>';

?>

<div>

<?php if(isset($discount_array['products'][$current_product_id])) {

		$product_discount_array = $discount_array['products'][$current_product_id];
	
		?>
	
		<div class="discount-table">
			
			<div class="table-head">
				
				<div>Bulk Buy Quantity</div>
				<div>Discount Amount</div>
				
			</div>

			<?php foreach($product_discount_array as $quantity => $discount) :

				$range_limits = explode('-', $quantity);
				$range_min = trim($range_limits[0]);
				$range_max = trim($range_limits[1]);
			?>
			
				<div class="table-body">

					<div class="quantity">

						<?php if($range_max > 10000) : ?>
							<?= $range_min ?>+
						<?php else : ?>
							<?= $range_min ?> - <?= $range_max ?>
						<?php endif; ?>

					</div>

					<div class="amount">
						<?= $discount ?>
					</div>

				</div>

			<?php endforeach; ?>
			
		</div>

		<?php 
	
		$is_table_added = true;

	} elseif(!empty($category_slugs_intersect)) {

		foreach ($product_category_slugs as $product_category_slug) {
			
			if (isset($discount_array['categories'][$product_category_slug])) {

				$category_discounts = $discount_array['categories'][$product_category_slug];

				?>

				<div class="discount-table">

					<div class="table-head">

						<div>Bulk Buy Quantity</div>
						<div>Discount Amount</div>

					</div>

					<?php foreach($category_discounts as $quantity => $discount) :

						$range_limits = explode('-', $quantity);
						$range_min = trim($range_limits[0]);
						$range_max = trim($range_limits[1]);
					?>

						<div class="table-body">

							<div class="quantity">

								<?php if($range_max > 10000) : ?>
									<?= $range_min ?>+
								<?php else : ?>
									<?= $range_min ?> - <?= $range_max ?>
								<?php endif; ?>

							</div>

							<div class="amount">
								<?= $discount ?>
							</div>

						</div>

					<?php endforeach; ?>

				</div>
	
				<?php

				$is_table_added = true;
				
			} 
			
		}

	} else { ?> 

			<script>
				document.querySelector('.product-after-element[data-dropdown="discount-table"]').remove();
			</script>

		<?php

	}
	
?>

</div>
<?php

global $product;
$product_id = $product->get_id();
$product = wc_get_product( $product_id );
$dimensions = [];

if ( $product->is_type( 'simple' ) ) {
	
	$dimensions["Length"] = $product->get_length() . get_option( 'woocommerce_dimension_unit' );
	$dimensions["Width"] = $product->get_width() . get_option( 'woocommerce_dimension_unit' );
	$dimensions["Height"] = $product->get_height() . get_option( 'woocommerce_dimension_unit' );
	$dimensions["Weight"] = $product->get_weight() . get_option( 'woocommerce_weight_unit' );
	
}

?>

<?php if($dimensions) : ?>

	<div class="dimensions-table">
		
		<?php foreach($dimensions as $k => $v) : ?>
			
			<div class="table-body">

				<div class="title">
					<?= $k ?>
				</div>

				<div class="value">
					<?= $v ?>
				</div>

			</div>

		<?php endforeach; ?>
		
	</div>

<?php else: ?>

	<script>document.querySelector('.product-after-element[data-dropdown="product-dimensions"]').remove()</script>

<?php endif; ?>
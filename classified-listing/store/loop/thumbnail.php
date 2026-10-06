<?php

use radiustheme\CLDirectory\RDTheme;
use RtclStore\Helpers\Functions as StoreFunction;
use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $store;

?>
<div class="store-thumb">
	<div class="store-logo">
		<?php if(RDTheme::$options['show_ad_count'] && ! StoreFunction::is_single_store()){
				$store->the_metas();
			}
		?>
		<?php $store->the_logo(); ?>
	</div>
	<div class="store-title">
		 
        <h3 class="rtcl-store-title"><a href="<?php echo esc_url( $store->get_the_permalink() ); ?>"> <?php echo esc_html($store->get_the_title()); ?></a> </h3>
        
		<?php if ( $store->is_rating_enable() && $store->get_review_counts() && RDTheme::$options['show_store_ratings']): ?>
			<div class="store-rating">
				<?php echo wp_kses_post( Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ) ); ?>
				<span class="reviews-rating-count">(<?php echo absint( $store->get_review_counts() ); ?>)</span>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
/**
 * Listing meta
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;

global $listing;

if ( ! $listing->can_show_date() && ! $listing->can_show_user() && ! $listing->can_show_location() && ! $listing->can_show_views() ) {
	return;
}

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
$show_phone = ! empty( $archive_settings['display_options'] ) && in_array( 'phone', $archive_settings['display_options'] );
$phone = get_post_meta( $listing->get_id(), 'phone', true );

?>
<ul class="entry-meta">
	<?php if ( $listing->has_location() && $listing->can_show_location() ): ?>
		<li><i class="map-marker-cl-icon"></i><?php $listing->the_locations(); ?></li>
	<?php endif; ?>
	<?php if(!empty($phone) && $show_phone): ?>
		<li><i class="phone-call-cl-icon"></i><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></li>
	<?php endif;?>
	<?php if ( $listing->can_show_date() ): ?>
		<li class="updated"><i class="far fa-clock"></i><?php $listing->the_time(); ?></li>
	<?php endif; ?>
	<?php if ( $listing->can_show_views() ): ?>
		<li class="rt-views">
			<i class="far fa-eye"></i>
			<?php
			// translators: %s: number of views.
			echo esc_html( sprintf( _n( "%s view", "%s views", $listing->get_view_counts(), 'cldirectory' ),
				number_format_i18n( $listing->get_view_counts() ) ) ); ?>
		</li>
	<?php endif; ?>
</ul>

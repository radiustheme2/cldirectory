<?php
/**
 * @var array $data
 * @var bool  $can_search_by_keyword
 * @since   1.0
 * @version 1.0
 *
 * @author  RadiusTheme
 * @package
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use Rtcl\Resources\Options as RtclOptions;
use RtclPro\Helpers\Options;
use RtclPro\Helpers\Fns;


$currency = Functions::get_currency_symbol();
extract( $data );
$loc_text = esc_attr__( 'All Cities', 'cldirectory' );
$cat_text = esc_attr__( 'All Categories', 'cldirectory' );
$typ_text = esc_attr__( 'Listing Type', 'cldirectory' );
?>


<div class="listing-grid-box">
	<form action="<?php echo esc_url( Functions::get_filter_form_url() ); ?>" class="advance-search-form map-search-form rtcl-widget-search-form is-preloader">
		<?php $permalink_structure = get_option( 'permalink_structure' ); ?>
		<?php if ( ! $permalink_structure ) : ?>
            <input type="hidden" name="post_type" value="rtcl_listing">
		<?php endif; ?>
		<div class="search-box">
			<?php if ( $can_search_by_keyword ): ?>
				<div class="search-item search-keyword">
					<div class="input-group">
						<input type="text" data-type="listing" name="q" class="rtcl-autocomplete form-control"
								placeholder="<?php esc_attr_e( 'Enter Keyword here ...', 'cldirectory' ); ?>"
								value="<?php if ( isset( $_GET['q'] ) ) {
									echo esc_attr( $_GET['q'] );
								} ?>"/>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( $can_search_by_category ): ?>
				<div class="search-item search-select rtin-category">
					<?php
					wp_dropdown_categories(
						[
							'show_option_none'  => $cat_text,
							'option_none_value' => '',
							'taxonomy'          => rtcl()->category,
							'name'              => 'rtcl_category',
							'id'                => 'rtcl-category-search-' . wp_rand(),
							'class'             => 'select2 rtcl-category-search',
							'selected'          => get_query_var( 'rtcl_category' ),
							'hierarchical'      => true,
							'value_field'       => 'slug',
							'depth'             => Functions::get_category_depth_limit(),
							'show_count'        => false,
							'hide_empty'        => false,
						]
					);
					?>
				</div>
			<?php endif; ?>
			<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && 'local' === Functions::location_type()  && $can_search_by_location): ?>
				<div class="search-item search-select rtin-location">
					<?php
					wp_dropdown_categories(
						[
							'show_option_none'  => $loc_text,
							'option_none_value' => '',
							'taxonomy'          => rtcl()->location,
							'name'              => 'rtcl_location',
							'id'                => 'rtcl-location-search-' . wp_rand(),
							'class'             => 'select2 rtcl-location-search',
							'selected'          => get_query_var( 'rtcl_location' ),
							'hierarchical'      => true,
							'value_field'       => 'slug',
							'depth'             => Functions::get_location_depth_limit(),
							'show_count'        => false,
							'hide_empty'        => false,
						]
					);
					?>
				</div>
			<?php endif; ?>
		</div>
		<div class="search-box-2">
			<?php if($can_search_by_radius_search): ?>
				<div class="distance-search search-item">
					<?php
					$rs_data = RtclOptions::radius_search_options();
					?>
					<div class="form-group ws-item ws-location">
						<h4 class="inner-title"><?php esc_html_e("Select a location","cldirectory"); ?></h4>
						<div class="rtcl-geo-address-field">
							<input type="text" name="geo_address" autocomplete="off"
									value="<?php echo ! empty( $_GET['geo_address'] ) ? esc_attr( $_GET['geo_address'] ) : '' ?>"
									placeholder="<?php esc_attr_e( "Select a location", "cldirectory" ) ?>"
									class="form-control rtcl-geo-address-input"/>
							<i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
							<input type="hidden" class="latitude" name="center_lat"
									value="<?php echo ! empty( $_GET['center_lat'] ) ? esc_attr( $_GET['center_lat'] ) : '' ?>">
							<input type="hidden" class="longitude" name="center_lng"
									value="<?php echo ! empty( $_GET['center_lng'] ) ? esc_attr( $_GET['center_lng'] ) : '' ?>">
						</div>
						<div class="rtcl-range-slider-field">
							<div class="rtcl-range-label">
								<h4 class="inner-title"><?php esc_html_e( 'Radius', 'cldirectory' ); ?></h4>
								<span class="rtcl-range-value">
									<?php echo ! empty( $_GET['distance'] ) ? absint( $_GET['distance'] ) : 30 ?>
									<?php in_array( $rs_data['units'], [
										'km',
										'kilometers'
									] ) ? esc_html_e( "km", "cldirectory" ) : esc_html_e( "Miles", "cldirectory" ); ?></span>
							</div>
							<input type="range" class="form-control-range rtcl-range-slider-input" name="distance"
									min="0"
									max="<?php echo absint( $rs_data['max_distance'] ) ?>"
									value="<?php echo esc_attr( isset( $_GET['distance'] ) ? sanitize_text_field( wp_unslash( $_GET['distance'] ) ) : $rs_data['default_distance'] ); ?>">
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( $can_search_by_price ): ?>
				<div class="search-item price-item-box">
					<?php if ( RDTheme::$options['listing_price_search_type'] == 'range' ) { ?>
						<div class="price-range">
							<h4 class="inner-title"><?php esc_html_e( 'Price Range', 'cldirectory' ); ?></h4>
							<?php
							$currency  = Functions::get_currency_symbol();
							$data_form = '';
							$data_to   = '';
							if ( isset( $_GET['filters']['price']['min'] ) ) {
								$data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
							}
							if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
								$data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
							}

							?>
							<input type="number"
									class="ion-rangeslider" <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_to ); ?>
									data-min="<?php echo isset( $min_price ) && ! empty( $min_price ) ? esc_attr( $min_price ) : 0; ?>"
									data-max="<?php echo isset( $max_price ) && ! empty( $max_price ) ? esc_attr( $max_price ) : 80000; ?>"
									data-prefix="<?php echo esc_html( $currency ) ?>"/>
							<input type="hidden" class="min-volumn" name="filters[price][min]"
									value="<?php if ( isset( $_GET['filters']['price']['min'] ) ) {
										echo absint( $_GET['filters']['price']['min'] );
									} ?>">
							<input type="hidden" class="max-volumn" name="filters[price][max]"
									value="<?php if ( isset( $_GET['filters']['price']['max'] ) ) {
										echo absint( $_GET['filters']['price']['max'] );
									} ?>">
						</div>
					<?php } else { ?>
						<!-- Price fields -->
						<div class="form-group">
							<h4 class="inner-title"><?php esc_html_e( 'Price Range', 'cldirectory' ); ?></h4>
							<div class="row">
								<div class="col-md-6 col-xs-6">
									<input type="text" name="filters[price][min]" class="form-control"
											placeholder="<?php esc_attr_e( 'min', 'cldirectory' ); ?>"
											value="<?php if ( isset( $_GET['filters']['price'] ) ) {
												echo esc_attr( $_GET['filters']['price']['min'] );
											} ?>">
								</div>
								<div class="col-md-6 col-xs-6">
									<input type="text" name="filters[price][max]" class="form-control"
											placeholder="<?php esc_attr_e( 'max', 'cldirectory' ); ?>"
											value="<?php if ( isset( $_GET['filters']['price'] ) ) {
												echo esc_attr( $_GET['filters']['price']['max'] );
											} ?>">
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php endif; ?>
			<?php if ( $can_search_by_custom_field ): ?>
				<div class="search-item-custom-field">
					<?php 
						$hide_group_id = isset( RDTheme::$options['custom_fields_search_items'] ) ? RDTheme::$options['custom_fields_search_items'] : array();

						$all_group_ids=Functions::get_cfg_ids();
						$args      = [
							'is_searchable'     => true,
							'exclude_group_ids' => $hide_group_id,
						];
						$fields_id = Functions::get_cf_ids( $args );

						$html = '';
						foreach ( $fields_id as $field ) {
							$html .= Listing_Functions::get_advanced_search_field_html( $field,true );
						}
						Functions::print_html( $html, true );
					?>
				</div>
			<?php endif; ?>	
		</div>
		<div class="search-item search-btn">
			<button type="submit" class="submit-btn">
				<i class="search-cl-icon"></i>
				<?php esc_html_e( 'Search', 'cldirectory' ); ?>
			</button>
		</div>
		
	</form>
</div>

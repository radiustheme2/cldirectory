<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Text;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\CLDirectory\Helper;
use radiustheme\CLDirectory\RDTheme;
use RtclMarketplace\Hooks\ActionHooks;
use RtclClaimListing\Helpers\Functions as ClaimFunctions;

global $listing;

if ( ! $listing ) {
	return;
}

$average_rating   = $listing->get_average_rating();
$rating_count     = $listing->get_rating_count();

$can_report_abuse = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_report_abuse', '', 'checkbox' ) ? true : false;

$single_settings = Functions::get_option( 'rtcl_single_listing_settings' );
$show_phone      = ! empty( $single_settings['display_options_detail'] ) && in_array( 'phone', $single_settings['display_options_detail'] );

$phone = get_post_meta( $listing->get_id(), 'phone', true );
if (Functions::isEnableFb()){
	$logo_id = get_post_meta( get_the_id(), 'listing_logo', true );
	$brand_logo_id = $logo_id[0] ?? '';
} else {
	$logo_id = get_post_meta( get_the_id(), 'listing_logo_img', true );
	$brand_logo_id = $logo_id;
}

$single_listing_style = Helper::listing_single_style();

?>

<div class="listing-heading" id="listing-home">
    <div class="listing-heading-top">
        <?php if (!empty($brand_logo_id)) { ?>
        <div class="listing-header-thumb">
            <?php 
                if (!empty($brand_logo_id)) {
                    echo wp_get_attachment_image( $brand_logo_id, 'thumbnail' );
                } else {
                    echo wp_kses_post( $listing->get_the_thumbnail( 'thumbnail' ) );
                } 
            ?>
        </div>
        <?php } ?>
        <div class="listing-heading-content">
            <ul class="heading-top-meta">
                <li><?php Helper::get_formated_business_hour($listing); ?></li>
                <li><?php $listing->the_badges(); ?></li>
                <?php if ( ! empty( $rating_count ) ): ?>
                    <li class="listing-rating">
                        <div class="item-icon">
                            <?php echo wp_kses_post( Functions::get_rating_html( $average_rating, $rating_count ) ); ?>
                        </div>
                        <div class="item-text"><?php echo wp_kses_post( apply_filters( 'cldirectory_rating_count_format',
                                                                        // translators: %s: number of reviews.
                                                                        sprintf( __( '(<span>%s</span>) Reviews', 'cldirectory' ), esc_html( $rating_count ) ) ) ); ?></div>
                    </li>
                <?php endif; ?>
            </ul>
            
            <h1 class="listing-title"><?php $listing->the_title(); ?></h1>
            <div class="details-listing-meta">
                <ul class="entry-meta">
                    <?php if($listing->can_show_category()){ ?>
                        <li>
                            <?php
                            Helper::get_listing_category($listing);
                            ?>
                        </li>
                    <?php } ?>
                    <?php if(!empty($phone) && $show_phone): ?>
                        <li><i class="phone-call-cl-icon"></i><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></li>
                    <?php endif;?>
                    <?php if ( current( $listing->user_contact_location_at_single() ) ): ?>
                        <li>
                            <i class="map-marker-cl-icon"></i><?php echo wp_kses_post( implode( '<span class="rtcl-delimiter">,</span> ', $listing->user_contact_location_at_single() ) ); ?>
                        </li>
                    <?php endif; ?>
                    <?php if ( $listing->can_show_date() ): ?>
                        <li><i class="calendar-cl-icon"></i><?php $listing->the_time(); ?></li>
                    <?php endif; ?>
                    <?php if ( $listing->can_show_views() ): ?>
                        <li>
                            <i class="eye-alt-cl-icon"></i><?php
						// translators: %s: number of views.
						echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'cldirectory' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </div>
        </div>
        
    </div>
    <div class="listing-heading-right">
	    <?php if ( $listing->can_show_price() && $single_listing_style !=2): ?>
            <div class="listing-price-wrap">
			    <?php $max_price=get_post_meta( $listing->get_id(), '_rtcl_max_price', true );
			    $price_type=$listing->get_pricing_type();
			    ?>
                <div class="listing-price"><span><?php esc_html_e('Price:','cldirectory'); ?></span><?php echo wp_kses_post( $listing->get_price_html() ); ?></div>

            </div>
	    <?php endif; ?>
        <?php
            if ( class_exists('RtclMarketplace') ) {
                ActionHooks::add_buy_button();
            }
        ?>
        <?php if ( RDTheme::$options['show_listing_button_area'] ) : ?>
            <div class="button-area">
                <ul>
                    <li><?php echo wp_kses_post( Functions::get_favourites_link( $listing->get_id() ) ); ?></li>
                    <li>
                        <?php if ( Fns::is_enable_compare() ) {
                            $compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
                            $selected_class = '';
                            if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
                                $selected_class = ' selected';
                            }
                            ?>
                            <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#"
                                data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                                <i class="compare-cl-icon"></i>
                            </a>
                        <?php } ?>
                    </li>
                    <?php if ( $can_report_abuse ): ?>
                        <li>
                            <?php if ( is_user_logged_in() ): ?>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#rtcl-report-abuse-modal">
                                    <i class='fas fa-bug'></i>
                                    <?php echo esc_html( Text::report_abuse() ); ?>
                                </a>
                            <?php else: ?>
                                <a href="javascript:void(0)" class="rtcl-require-login">
                                    <i class='fas fa-bug'></i>
                                    <?php echo esc_html( Text::report_abuse() ); ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                    <?php if($listing->the_social_share(false)) : ?>
                    <li>
                        <a href="#" id="share-btn"><i class="share-cl-icon"></i></a>
                        <div class="share-icon">
                            <?php $listing->the_social_share(); ?>
                        </div>
                    </li>
                    <?php endif; ?>

                    <li><a href="#" onclick="window.print();"><i class="printer-cl-icon"></i></a></li>

	                <?php if ( function_exists( 'rtclClaimListing' ) && ClaimFunctions::claim_listing_enable() ){ ?>
                        <li class='report-abuse-li' data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Claim", "cldirectory" ) ?>">
			                <?php if ( is_user_logged_in() ): ?>
                                <span data-bs-toggle="tooltip" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#rtcl-claim-listing-modal">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </a>
                                        </span>
			                <?php else: ?>
                                <a href="javascript:void(0)" data-bs-toggle="tooltip" class="rtcl-require-login" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                                    <i class="fas fa-exclamation-circle"></i>
                                </a>
			                <?php endif; ?>
                        </li>
	                <?php } ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade rtcl-bs-modal" id="rtcl-report-abuse-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rtcl-report-abuse-form" class="form-vertical">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="rtcl-report-abuse-modal-label"><?php esc_html_e( 'Report Abuse', 'cldirectory' ); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rtcl-report-abuse-message"><?php esc_html_e( 'Your Complaint', 'cldirectory' ); ?>
                            <span class="rtcl-star">*</span></label>
                        <textarea name="message" class="form-control" id="rtcl-report-abuse-message" rows="3"
                                  placeholder="<?php esc_attr_e( 'Message... ', 'cldirectory' ); ?>"
                                  required></textarea>
                    </div>
                    <div id="rtcl-report-abuse-g-recaptcha"></div>
                    <div id="rtcl-report-abuse-message-display"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Submit', 'cldirectory' ); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php do_action( 'rtcl_single_listing_after_action', $listing->get_id() ); ?>




<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use radiustheme\CLDirectory\Helper;

Helper::requires( 'customizer/controls/switch-control.php' );
Helper::requires( 'customizer/controls/image-radio-control.php' );
Helper::requires( 'customizer/controls/separator-control.php' );
Helper::requires( 'customizer/controls/gallery-control.php' );
Helper::requires( 'customizer/controls/select2-control.php' );
Helper::requires( 'customizer/controls/heading-control.php' );
Helper::requires( 'customizer/controls/control-checkbox-multiple.php' );
Helper::requires( 'customizer/controls/alfa-color.php' );
Helper::requires( 'customizer/typography/typography-controls.php' );
Helper::requires( 'customizer/typography/typography-customizer.php' );
Helper::requires( 'customizer/controls/sanitization.php' );
Helper::requires( 'customizer/customizer.php' );
Helper::requires( 'customizer/settings/general.php' );
Helper::requires( 'customizer/settings/header.php' );
Helper::requires( 'customizer/settings/breadcrumb.php' );
Helper::requires( 'customizer/settings/footer.php' );
Helper::requires( 'customizer/settings/color.php' );
Helper::requires( 'customizer/settings/blog.php' );
Helper::requires( 'customizer/settings/post.php' );
Helper::requires( 'customizer/settings/social-share-settings.php' );

Helper::requires( 'customizer/settings/footer-cta-banner.php' );

Helper::requires( 'customizer/settings/error.php' );
Helper::requires( 'customizer/settings/contact-info.php' );
Helper::requires( 'customizer/settings/blog-layout.php' );
Helper::requires( 'customizer/settings/single-post-layout.php' );
Helper::requires( 'customizer/settings/page-layout.php' );
Helper::requires( 'customizer/settings/error-layout.php' );

if ( class_exists( 'Rtcl' ) ) {
	Helper::requires( 'customizer/settings/listing-archive-layout.php' );
	Helper::requires( 'customizer/settings/author-page-layout.php' );
	Helper::requires( 'customizer/settings/listing-single-layout.php' );
	Helper::requires( 'customizer/settings/listings.php' );
	Helper::requires( 'customizer/settings/listing-search.php' );
}

if ( class_exists( 'RtclStore' ) ) {
	Helper::requires( 'customizer/settings/store-archive-layout.php' );
	Helper::requires( 'customizer/settings/store.php' );
	Helper::requires( 'customizer/settings/store-single-layout.php' );
}

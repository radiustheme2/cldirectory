<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_top_info = RDTheme::$options['contact_address'] || RDTheme::$options['contact_phone'] || RDTheme::$options['contact_email'] || RDTheme::$options['contact_website'] ? true
	: false;
$socials      = Helper::socials();

if ( ! $has_top_info || ! $socials ) {
	return;
}
$header_container = 'container';
if ( 'fullwidth' == RDTheme::$header_width ) {
	$header_container = 'container-fluid';
}
$full_container=RDTheme::$header_width==='fullwidth' ? 'has-full-container':'no-full-container';
$email_text=RDTheme::$options['email_text'] ? RDTheme::$options['email_text']:'';
$address=RDTheme::$options['contact_address'] ? RDTheme::$options['contact_address']:'';
$phone_text=RDTheme::$options['phone_text'] ? RDTheme::$options['phone_text']:'';
$social_text=RDTheme::$options['social_text'] ? RDTheme::$options['social_text']:'';

$mobile_topbar_header=RDTheme::$options['header_mobile_topbar'] ? 'has-mobile-topbar':'hide-mobile-topbar';
?>

<div id="tophead" class="header-topbar <?php echo esc_attr( $full_container ); ?> <?php echo esc_attr($mobile_topbar_header); ?>">
    <div class="<?php echo esc_attr( $header_container ); ?>">
        <div class="topbar-content-wrapper">
			<?php if ( $has_top_info ): ?>
                <ul class="topbar-left">
                    <?php if($address){ ?>
                        <li class="item-address"><i class="map-marker-alt-cl-icon"></i><span><?php echo esc_html( $address ); ?></span></li>
                    <?php } ?>
                    <?php if ( RDTheme::$options['contact_phone'] ): ?>
                        <li class="item-phone"><i class="mobile-cl-icon"></i><?php if($phone_text) echo wp_kses_post($phone_text); ?><a href="tel:<?php echo esc_attr(RDTheme::$options['contact_phone']); ?>"><span><?php echo esc_html( RDTheme::$options['contact_phone'] ); ?></span></a></li>
                    <?php endif; ?>
                    <?php if ( RDTheme::$options['contact_email'] ): ?>
                        <li class="item-location"><i class="envelope-cl-icon"></i><?php if($email_text) echo wp_kses_post($email_text); ?><span><?php echo esc_html( RDTheme::$options['contact_email'] ); ?></span></li>
                    <?php endif; ?>
                </ul>
			<?php endif; ?>
			<?php if ( $socials ): ?>
                <ul class="topbar-right">
                    <?php if ( $socials ): ?>
                        <li class="social-icon">
                            <label><i class="share-cl-icon"></i><?php if($social_text) echo wp_kses_post($social_text); ?></label>
                            <?php foreach ( $socials as $social ): ?>
                                <a target="_blank" href="<?php echo esc_url( $social['url'] ); ?>"><i class="<?php echo esc_attr( $social['icon'] ); ?>"></i></a>
                            <?php endforeach; ?>
                        </li>
                    <?php endif; ?>
                </ul>
			<?php endif; ?>
        </div>
    </div>
</div>
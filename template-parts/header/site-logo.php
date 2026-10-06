<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\CLDirectory\Helper;
use radiustheme\CLDirectory\RDTheme;

$custom_logo_id    = get_theme_mod( 'custom_logo' );
$main_logo         = ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo'], 'full' ) : '';
$light_logo        = ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo_light'], 'full' )
	:'';

$single_listing_style = Helper::listing_single_style();
$logo2='';
if ( RDTheme::$has_tr_header || ($single_listing_style==2 && is_singular('rtcl_listing'))) {
	$logo = $light_logo;
    $logo2=$main_logo;
} else {
	$logo = $main_logo;
}
$logo_class=$logo ? 'has-icon-logo':'no-icon-logo';
?>

<div class="site-branding-wrap">
    <div class="site-branding <?php  echo esc_attr($logo_class);?>">
		<?php if ( ! empty( $logo ) ){ ?>
            <a class="custom-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img
                    class="img-fluid" src="<?php echo esc_url( $logo[0] ); ?>"
                    width="<?php echo esc_attr( $logo[1] ); ?>"
                    height="<?php echo esc_attr( $logo[2] ); ?>"
                    alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                >
            </a>
		<?php }
            if(!empty($logo2)){?>
                <a class="custom-logo-dark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img
                        class="img-fluid" src="<?php echo esc_url( $logo2[0] ); ?>"
                        width="<?php echo esc_attr( $logo2[1] ); ?>"
                        height="<?php echo esc_attr( $logo2[2] ); ?>"
                        alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                    >
                </a>
            <?php } elseif(empty($logo)){ 
            ?>
            <h1 class="site-title">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'cldirectory' ); ?>" rel="home">
					<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                </a>
            </h1>
		<?php } ?>
    </div>
</div>

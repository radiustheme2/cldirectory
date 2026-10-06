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

$footer_columns = 0;

foreach ( range( 1, 4 ) as $i ) {
	if ( is_active_sidebar( 'footer-' . $i ) ) {
		$footer_columns ++;
	}
}

switch ( $footer_columns ) {
	case '1':
		$footer_class = 'col-sm-12 col-12';
		break;
	case '2':
		$footer_class = 'col-sm-6 col-12';
		break;
	case '3':
		$footer_class = 'col-md-4 col-sm-12 col-12';
		break;
	default:
		$footer_class = 'col-lg-3 col-sm-6 col-12';
}

$is_border       = RDTheme::$footer_border ? 'is-border' : '';
$is_footer_cta=RDTheme::$has_footer_cta_banner ==1 || RDTheme::$has_footer_cta_banner ==='on' ? 'has-footer-cta':'no-footer-cta';

?>
<footer id="site-footer" class="site-footer footer-wrap footer-style-1 <?php echo esc_attr( $is_border." ".$is_footer_cta ) ?>">
	<?php if ( $footer_columns ): ?>
        <div class="main-footer">
            <div class="container">
                <div class="row">
					<?php
					foreach ( range( 1, 4 ) as $i ) {
						if ( ! is_active_sidebar( 'footer-' . $i ) ) {
							continue;
						}
						echo '<div class="' . esc_attr( $footer_class ) . '">';
						dynamic_sidebar( 'footer-' . $i );
						echo '</div>';
					}
					?>
                </div>
            </div>
			<div class="footer-shape wow fadeInUp animated" data-wow-delay="0.5s" data-wow-duration="1s">
				<img src="<?php echo esc_url(CLDIRECTORY_ASSETS_URL."/img/footer-shape.svg"); ?>" width="403" height="165" alt="<?php __('Footer Shape','cldirectory'); ?>">
			</div>
        </div>
		
	<?php endif; ?>
	<?php if ( RDTheme::$options['copyright_area'] ): ?>
        <div class="footer-bottom">
            <div class="container">	
				<div class="copyright-wrap">
					<p class="footer-copyright">
						<?php
						echo wp_kses_post( RDTheme::$options['copyright_text'])
						?>
					</p>
					
				</div>
            </div>
        </div>
	<?php endif; ?>
</footer>
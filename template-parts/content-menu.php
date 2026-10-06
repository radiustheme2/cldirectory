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

?>

    <header id="site-header" class="site-header">
		<div id="header-<?php echo esc_attr(RDTheme::$header_style); ?>" class="header-area">
			<?php
			if ( RDTheme::$has_top_bar ) {
				get_template_part( 'template-parts/header/header-top', '1' );
			}
			get_template_part( 'template-parts/header/header', RDTheme::$header_style );
			?>
		</div>
    </header>

	<?php get_template_part( 'template-parts/header/header', 'offscreen' ); ?>
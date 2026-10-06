<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.4
 */

namespace radiustheme\CLDirectory;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

</div><!-- #content -->
<?php 

if ( (RDTheme::$has_footer_cta_banner ==1 || RDTheme::$has_footer_cta_banner ==='on' )) {
	get_template_part( 'template-parts/rt', 'cta-banner' );
}

?>

<?php
get_template_part( 'template-parts/footer/footer', RDTheme::$footer_style ); ?>
</div><!-- #page -->
<?php wp_footer();?>
</body>
</html>
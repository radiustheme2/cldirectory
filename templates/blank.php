<?php
/**
 * Template Name: Blank Template
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
    <div id="primary" class="elementor-page-content">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
    </div>
	<?php get_footer();
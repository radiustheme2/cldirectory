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

$cols = RDTheme::$options['blog_archive_columns'];

$post_class = Helper::has_sidebar() ? 'row-cols-sm-'.$cols.' row-cols-1' : 'row-cols-sm-'.$cols.' row-cols-1';
$is_blog_style_2 = ( is_home() || is_archive() ) && RDTheme::$options['blog_style'] == 'style2';
$grid_style = $is_blog_style_2 ? 'style2 blog-grid' : 'style1 blog-list';
get_header();
?>
    <main class="site-main blog-grid blog-grid-inner content-area blog-content rtcl-widget-border-enable rtcl-widget-is-sticky <?php echo esc_attr( $grid_style ); ?>">
        <div class="container">
            <div class="row">
                <div class="<?php Helper::the_layout_class(); ?>">
                    <div class="main-post-content">
						<?php if ( have_posts() ) : ?>
							<?php
							
							if ( $is_blog_style_2 ) {
								echo '<div class="row ' . esc_attr( $post_class ) . '">';
								
								while ( have_posts() ) : the_post();
									echo '<div class="col">';
										get_template_part( 'template-parts/content-grid' );
									echo '</div>';
								endwhile;
								echo '</div>';
							} else {
								
								while ( have_posts() ) : the_post();
									echo '<div class="post-list-item">';
									get_template_part( 'template-parts/content' );
									echo '</div>';
									
								endwhile;
							}
							?>
						<?php else: ?>
							<?php get_template_part( 'template-parts/content', 'none' ); ?>
						<?php endif; ?>
                    </div>

					<?php
					if ( RDTheme::$options['blog_related_posts'] ) {
						get_template_part( 'template-parts/pagination' );
					}
					?>
                </div>
				<?php
                    if ( Helper::has_sidebar() ) {
                        get_sidebar();
                    }
				?>
            </div>
        </div>
    </main>

<?php

get_footer();
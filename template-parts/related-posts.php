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

$rt_post_cat = wp_get_object_terms( $post->ID, 'category', [ 'fields' => 'ids' ] );


// arguments
$args = [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'tax_query'      => [
		[
			'taxonomy' => 'category',
			'field'    => 'id',
			'terms'    => $rt_post_cat,
		],
	],
	'post__not_in'   => [ $post->ID ],
];

$query = new \WP_Query( $args );
get_header();
?>
<?php 
if ( $query->have_posts() ) :?>
<div class="related-posts blog-grid blog-grid-inner blog-content style2 blog">
    <div class="container">
        <div class="main-post-content">
            <div class="section-title-wrapper">
                <h2 class="rt-section-title"><?php echo esc_html__( 'Related Blogs', 'cldirectory' ); ?></h2>
                <div class="rt-heading-shape"><i class="icon-wheel"></i></div>
            </div>
            <div class="row row-cols-sm-3 row-cols-1">
                <?php
                if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        get_template_part( 'template-parts/content-grid' );
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
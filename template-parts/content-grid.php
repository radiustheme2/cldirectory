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

$comments_number = get_comments_number();
$comments_text   = sprintf( '(%s)', number_format_i18n( $comments_number ) );
$length          = RDTheme::$options['excerpt_length'];
$has_thumbnail   = has_post_thumbnail() ? ' has-thumbnail' : ' has-no-thumbnail';
$post_class      = 'blog-box grid-style is-date';
$post_class      .= $has_thumbnail;

?>


<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
    <?php if ( has_post_thumbnail() ):
        $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'rdtheme-size1' );
        ?>
        <div class="post-img is-date">
            <a href="<?php the_permalink(); ?>">
                <div class="thumb-bg">
                    <?php the_post_thumbnail('rdtheme-size2') ?>
                </div>
            </a>

        </div>
    <?php endif; ?>
    <div class="post-content">

        <div class="post-meta is_dots">

            <ul class="entry-meta">
                <?php if ( RDTheme::$options['blog_cat_visibility'] && has_category() ): ?>
                    <li class="category"><?php the_category( ' ' ); ?></li>
                <?php endif; ?>
                <?php if ( RDTheme::$options['blog_comment_num'] ): ?>
                    <li><i class="icon-comment" aria-hidden="true"></i><?php echo esc_html( $comments_text ); ?></li>
                <?php endif; ?>
                <?php if ( RDTheme::$options['blog_archive_reading_time'] ): ?>
                    <li>
                        <span data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr__( 'Reading Time', 'cldirectory' ) ?>" data-original-title="<?php echo esc_attr__( 'Reading Time', 'cldirectory' ) ?>">
                            <?php echo wp_kses_post( Helper::reading_time_count( get_the_content(), true ) ); ?>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>

        </div>

        <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
        <div class="post-excerprt-wrap">
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), $length ) ); ?></p>
        </div>
        <ul class="footer-meta">
            <?php if ( RDTheme::$options['blog_date'] ): ?>
                <li><i class="calendar-cl-icon"></i><span class="updated published"><?php the_time( get_option( 'date_format' ) ); ?></span></li>
            <?php endif; ?>
            <?php if ( RDTheme::$options['blog_author_name'] ): ?>
                <li>
                    <i class="user-alt-cl-icon"></i><span class="vcard author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author(); ?></a>
                    </span>
                </li>
            <?php endif; ?>
            
        </ul>
    </div>
</article>

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

global $wp_query;

$max_num_pages = isset( $max_num_pages ) ? $max_num_pages : false;

$max = $max_num_pages ? $max_num_pages : $wp_query->max_num_pages;
$max = intval( $max );

/** Stop execution if there's only 1 page */
if ( $max <= 1 ) {
	return;
}

if ( get_query_var( 'paged' ) ) {
	$paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
	$paged = get_query_var( 'page' );
} else {
	$paged = 1;
}

/**    Add current page to the array */
if ( $paged >= 1 ) {
	$links[] = $paged;
}

/**    Add the pages around the current page to the array */
if ( $paged >= 3 ) {
	$links[] = $paged - 1;
	$links[] = $paged - 2;
}

if ( ( $paged + 2 ) <= $max ) {
	$links[] = $paged + 2;
	$links[] = $paged + 1;
}

$previous_text = '<i class="angle-left-cl-icon"></i>';
$next_text     = '<i class="angle-right-cl-icon"></i>';

echo '<div class="pagination-number"><ul class="clearfix">' . "\n";

/**    Previous Post Link */
$previous_posts_link = get_previous_posts_link( $previous_text );
if ( $previous_posts_link ) {
	printf( '<li class="pagi-previous">%s</li>' . "\n", wp_kses_post( $previous_posts_link ) );
}

/**    Link to first page, plus ellipses if necessary */
if ( ! in_array( 1, $links ) ) {
	printf( '<li%s><a href="%s">%s</a></li>' . "\n", ( 1 == $paged ) ? ' class="active"' : '', esc_url( get_pagenum_link( 1 ) ), '1' );

	if ( ! in_array( 2, $links ) ) {
		echo '<li><span>...</span></li>';
	}
}

/**    Link to current page, plus 2 pages in either direction if necessary */
sort( $links );
foreach ( (array) $links as $link ) {
	printf( '<li%s><a href="%s">%s</a></li>' . "\n", ( $paged == $link ) ? ' class="active"' : '', esc_url( get_pagenum_link( $link ) ), absint( $link ) );
}

/**    Link to last page, plus ellipses if necessary */
if ( ! in_array( $max, $links ) ) {
	if ( ! in_array( $max - 1, $links ) ) {
		echo '<li><span>...</span></li>' . "\n";
	}

	printf( '<li%s><a href="%s">%s</a></li>' . "\n", ( $paged == $max ) ? ' class="active"' : '', esc_url( get_pagenum_link( $max ) ), absint( $max ) );
}

/**    Next Post Link */
$next_posts_link = get_next_posts_link( $next_text, $max );
if ( $next_posts_link ) {
	printf( '<li class="pagi-next">%s</li>' . "\n", wp_kses_post( $next_posts_link ) );
}

echo '</ul></div>' . "\n";
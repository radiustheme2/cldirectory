<?php
/**
 * Pagination
 *
 * @author     RadiusTheme
 * @package    cldirectory/templates
 * @version    1.0.0
 */

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( 1 != $pages ) : ?>
    <nav aria-label="Page navigation" class="rtcl-pagination" role="navigation">
        <ul class="page-numbers">
			<?php if ( $paged > 1 && $showItems < $pages ) : ?>
                <li><a class="page-numbers" href="<?php echo esc_url( get_pagenum_link( $paged - 1 ) ); ?>"
                                         aria-label="Previous Page"><span aria-hidden="true"><i class="angle-left-cl-icon"></i></span>
                        </a></li>
			<?php endif; ?>
			<?php for ( $i = 1; $i <= $pages; $i ++ ): ?>
				<?php if ( $paged == $i ) : ?>
                    <li><span class="page-numbers current"><?php echo absint($i); ?></span>
                    </li>
				<?php else: ?>
                    <li><a class="page-numbers" href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>"><?php echo absint($i); ?></a></li>
				<?php endif; ?>
			<?php endfor; ?>
			<?php if ( $paged < $pages && $showItems < $pages ) : ?>
                <li><a class="page-numbers" href="<?php echo esc_url( get_pagenum_link( $paged + 1 ) ); ?>"
                                         aria-label="Next Page"><span aria-hidden="true"><i class="angle-right-cl-icon"></i></span> </a></li>
			<?php endif; ?>
        </ul>
    </nav>
<?php endif;
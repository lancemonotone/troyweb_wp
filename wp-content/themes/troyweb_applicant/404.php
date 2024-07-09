<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
?>
    <div class="layout">
        <div class="inner">
            <header class="page-header alignwide">
                <h2 class="header-lg"><?php esc_html_e( 'Nothing here', 'monotone' ); ?></h2>
            </header><!-- .page-header -->

            <div class="error-404 not-found default-max-width">
                <div class="page-content">
                    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'monotone' ); ?></p>
					<?php get_search_form(); ?>
                </div><!-- .page-content -->
            </div><!-- .error-404 -->
        </div>
    </div>
<?php
get_footer();

<?php

get_header();
?>

    <main class="container">
        <div class="row">
            <div class="col">
                <h1>
                    <?php
                    printf(
                    /* translators: %s: Search term. */
                        esc_html__( 'Search Results for "%s"', 'monotone' ),
                        '<span class="page-description search-term">' . esc_html( get_search_query() ) . '</span>'
                    );
                    ?>
                </h1>

                <p><?php
                    printf(
                        esc_html(
                        /* translators: %d: The number of search results. */
                            _n(
                                'We found %d result for your search.',
                                'We found %d results for your search.',
                                (int)$wp_query->found_posts,
                                'monotone'
                            )
                        ),
                        (int)$wp_query->found_posts
                    );
                    ?></p>

                <div class="search-part search-page">
                    <?php get_template_part( 'parts/searchform' ); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <span class="small-all-caps">Search Results</span>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <?php
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        ?>

                        <div class="">
                            <?php $thisTitle = get_the_title(); ?>
                            <h3 class="">
                                <a href="<?php the_permalink(); ?>" aria-label="Read more about <?php echo $thisTitle; ?>"><?php echo $thisTitle; ?></a>
                            </h3>

                            <div class="">
                                <div class="">
                                    <?php if ( function_exists( 'relevanssi_the_excerpt' ) ) {
                                        relevanssi_the_excerpt();
                                    } else { ?>
                                        <p> <?= get_the_excerpt() ?> </p>
                                    <?php } ?>
                                </div>

                                <div class="">
                                    <a href="<?php the_permalink(); ?>" class="button" aria-label="Read more about <?php echo $thisTitle; ?>">Read More</a>
                                </div>
                            </div>
                        </div>

                    <?php }
                }
                ?>
            </div>
        </div>

        <div class="layout spacer standard" aria-hidden="true" role="presentation"></div>

        <?php
        global $wp_query;

        $big = 999999999; // need an unlikely integer

        $pagination = paginate_links( [
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $wp_query->max_num_pages,
            'prev_next' => false,
            'type'      => 'array',
            'mid_size'  => 1,
        ] );

        if ( is_array( $pagination ) ) {
            echo '<nav aria-label="Pagination" role="navigation">';
            echo '<ul class="pagination-list">';
            foreach ( $pagination as $page_link ) {
                echo '<li>';
                echo '<span class="visually-hidden">Page </span>';
                echo $page_link;
                echo '</li>';
            }
            echo '</ul>';
            echo '</nav>';
        }
        ?>

        <div class="layout spacer standard" aria-hidden="true" role="presentation"></div>

    </main>
<?php get_footer();

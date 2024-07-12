<?php namespace monotone;

/**
 * This template must be used within the loop because
 */
?>

<span class="blog-posts d-grid column-gap-5">

    <?php while ( have_posts() ) {
        the_post();
        ?>

        <article <?php post_class( 'position-relative mt-0 mb-standard d-grid gap-2' ) ?>>

            <span class="edit-post-link position-absolute top-0 end-0 small d-none bg-white">
                <?= Blog::get_edit_post_link( get_the_ID(), 'pencil' ) ?>
            </span>

            <span class="text-muted small"><?= Blog::get_post_date() ?></span>

            <h2 class="fs-3"><?= Blog::get_linked_title() ?></h2>

            <span class="featured-image bg-body-secondary"><?= Blog::get_featured_image() ?></span>

            <p class="overflow-auto"><?= Blog::get_excerpt( get_the_ID(), 30 ) ?: ' ' ?></p>

            <footer class="small d-grid gap-1 mt-0 pt-3">
                <?php if ( $categories = Blog::get_term_links() ) { ?>
                    <span><?= __( 'Cats: ', 'monotone' ) ?></span>
                    <span><?= $categories ?></span>
                <?php } ?>
                <?php if ( $tags = Blog::get_term_links( get_the_ID(), 'post_tag' ) ) { ?>
                    <span><?= __( 'Tags: ', 'monotone' ) ?></span>
                    <span><?= $tags ?></span>
                <?php } ?>
            </footer>

            <span class="my-1 d-grid"><?= Blog::get_read_more() ?></span>

        </article>

    <?php } ?>

</span>
<?php Pagination::get_pagination(); ?>

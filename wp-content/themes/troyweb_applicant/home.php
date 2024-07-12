<?php namespace monotone;

get_header();
?>
    <?php if ( have_posts() ) { ?>

    <?php the_archive_title( '<h1>', '</h1>' ); ?>

    <?php if ( $description = get_the_archive_description() ) { ?>
        <div class="archive-description"><?= wp_kses_post( wpautop( $description ) ) ?></div>
    <?php } ?>

    <?php get_template_part( 'parts/blog_posts' ); ?>

<?php } else { ?>

    <p><?php
        /* translators: No posts found text. */
        esc_html_e( 'No posts found.', 'monotone' ); ?></p>

<?php } ?>

<?php get_footer();

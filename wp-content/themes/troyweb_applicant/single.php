<?php

namespace monotone; ?>

<?php get_header(); ?>

<main class="container">
    <div class="row">
        <div class="col">
            <h1><?php the_title(); ?></h1>
            <p><?php the_content(); ?></p>
        </div>
    </div>
</main>

<?php get_footer();

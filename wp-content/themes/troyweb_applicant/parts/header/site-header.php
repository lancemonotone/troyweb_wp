<?php

namespace monotone; ?>

<header id="masthead" class="container d-flex flex-wrap justify-content-between py-3 mb-4 border-bottom">

    <div class="search-part col-12 d-flex justify-content-end"> <!-- Edit: Added d-flex justify-content-end to align contents to the end -->
        <?php get_template_part('parts/searchform'); ?>
    </div>

    <div class="site-logo">
        <?php Branding::get_site_logo(); ?>
    </div>

    <?php get_template_part('parts/header/site-nav'); ?>
</header>
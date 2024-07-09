<?php

namespace monotone; ?>

<nav id="site-navigation" class="primary-navigation" aria-label="<?php esc_attr_e('Primary menu', 'monotone'); ?>">
    <div class="search-part">
        <?php get_template_part('parts/searchform'); ?>
    </div>

    <?php
    wp_nav_menu(
        [
            'theme_location'  => 'primary',
            'menu_class'      => 'offcanvas-body p-4 pt-0 p-lg-0',
            'container_class' => 'navbar-nav flex-row flex-wrap bd-navbar-nav',
            'items_wrap'      => '<ul id="primary-menu-list" class="%2$s">%3$s</ul>',
            'fallback_cb'     => false,
        ]
    );
    ?>
</nav>

<button id="primary-mobile-menu" class="d-sm-none" aria-controls="primary-menu-list" aria-expanded="false">
    <span class="button-icon">
        <?= SVG_Icons::get_svg('ui', 'menu') ?>
    </span>
    <span class="button-text">
        <?php esc_html_e('Menu', 'monotone'); ?>
    </span>
</button>
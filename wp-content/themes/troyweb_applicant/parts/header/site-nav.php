<?php

namespace monotone; ?>


<nav class="navbar navbar-expand-lg" role="navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'monotone' ); ?>">

    <div class="navbar-brand">
        <?php Branding::get_site_logo(); ?>
    </div>

    <!-- Brand and toggle get grouped for better mobile display -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primary-navigation" aria-controls="primary-navigation" aria-expanded="false" aria-label="<?php _e( 'Toggle navigation', 'monotone' ); ?>">
        <span class="navbar-toggler-icon"></span>
    </button>

    <?php
    wp_nav_menu( [
        'theme_location'  => 'primary',
        'depth'           => 2,
        'container'       => 'div',
        'container_class' => 'collapse navbar-collapse',
        'container_id'    => 'primary-navigation',
        'menu_class'      => 'navbar-nav ms-auto mb-2 mb-lg-0',
        'fallback_cb'     => '\WP_Bootstrap_Navwalker::fallback',
        'walker'          => new \WP_Bootstrap_Navwalker(),
    ] );
    ?>

</nav>


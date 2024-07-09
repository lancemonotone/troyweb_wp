<?php

namespace monotone; ?>

<nav class="navbar navbar-expand-md navbar-light" role="navigation" aria-label="<?php esc_attr_e('Primary menu', 'monotone'); ?>">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'monotone'); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        wp_nav_menu(array(
            'theme_location'    => 'primary',
            'depth'             => 2,
            'container'         => 'div',
            'container_class'   => 'collapse navbar-collapse',
            'container_id'      => 'bs-example-navbar-collapse-1',
            'menu_class'        => 'nav navbar-nav',
            'fallback_cb'       => '\WP_Bootstrap_Navwalker::fallback',
            'walker'            => new \WP_Bootstrap_Navwalker(),
        ));
        ?>
    </div>
</nav>
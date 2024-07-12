<?php namespace monotone; ?>

</div><!-- .container -->
</main>

<footer id="colophon" class="bg-secondary text-light text-center pt-5 py-3">
    <div class="container text-start">
        <?php get_template_part( 'parts/footer/footer-branding' ); ?>
    </div>
    <div class="container text-start">
        <div class="widget-area d-flex flex-wrap gap-5 justify-content-between">
            <div class="widget_nav_menu">
                <h3 class="widget-title"><?= esc_html__( 'Primary Menu', 'monotone' ) ?></h3>
                <?php // get footer menu using bootstrap utility classes
                wp_nav_menu( [
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'menu',
                    'depth'          => 1,
                    'fallback_cb'    => '\WP_Bootstrap_Navwalker::fallback',
                    'walker'         => new \WP_Bootstrap_Navwalker(),
                ] ); ?>
            </div>

            <?php get_template_part( 'parts/footer/footer-widgets' ); ?>
        </div>
    </div>
</footer>
</div> <!-- .site -->
<?php wp_footer(); ?>
</body>

</html>

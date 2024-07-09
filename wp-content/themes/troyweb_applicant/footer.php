<?php

namespace monotone; ?>

<footer id="colophon" class="bg-secondary text-light text-center py-3">
    <div class="container">
        <div class="row">
            <div class="col">
                <?php get_template_part('parts/footer/footer-branding'); ?>
            </div>
            <div class="col">
                <?php get_template_part('parts/footer/footer-widgets'); ?>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>
<?php

namespace monotone;

$id      = $args['id'] ?? '';
$classes = $args['classes'] ?? 'layout example';
$styles  = $args['styles'] ?? '';

if (get_sub_field('example_height') == 'tall') {
    $classes .= ' tall';
}

$dividerLabel = get_sub_field('example_label');
?>

<div id="<?= $id ?>" class="<?= $classes ?>" style="<?= $styles ?>">
    <div class="container">
        <div class="position-relative d-flex justify-content-end mb-4 mt-4 pt-2 border-top border-2 border-primary-light">
            <?php if ($dividerLabel) { ?>
                <span class="text-uppercase text-primary-light w-90 text-end small"><?= $dividerLabel ?></span>
            <?php } ?>
        </div>
    </div>
</div>
<?php

namespace monotone;

$section_header = get_sub_field( 'section_header' ) ?? '';
$heading_size   = get_sub_field( 'heading_size' ) ?? 'header-lg';
$content        = get_sub_field( 'content' ) ?? '';

$id      = $args[ 'id' ] ?? '';
$classes = $args[ 'classes' ] ?? '';
$styles  = $args[ 'styles' ] ?? '';
?>

<div id="<?= $id ?>"
     class="<?= $classes ?>"
     style="<?= $styles ?>">

    <div class="container">

        <?php if ( $section_header ) { ?>

            <h3 class="section-heading <?= $heading_size ?>"><?= $section_header; ?></h3>

        <?php } ?>

        <?php if ( $content ) { ?>

            <div class="card-body body-serif-lg"><?= $content ?></div>

        <?php } ?>

    </div>

</div>

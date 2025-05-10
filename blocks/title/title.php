<div id="<?php print $el_id; ?>" class="<?php print implode(' ', $el_class); ?>">
    <?php lh_print_block_style(self::$slug); ?>

    <?php lh_print_simple_acf_element($title, 'h1', 'lh-h1 lh-title-block') ?>
</div>
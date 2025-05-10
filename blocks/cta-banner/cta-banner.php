<section id="<?php echo esc_attr($el_id); ?>" class="<?php echo esc_attr(implode(' ', $el_class)); ?>">
    <?php lh_print_block_style(self::$slug); ?>

    <?php if (!empty($title)) : ?>
    <div class="section-title">
        <?php if (!empty($title_icon)) : ?>
        <span class="dashicons <?php echo esc_attr($title_icon); ?>"></span>
        <?php endif; ?>
        <?php
        $title_args = ['id' => $el_id . '-title'];
        lh_print_simple_acf_element($title, 'h1', 'lh-h1 lh-cta-banner-block', $title_args);
        ?>
    </div>
    <?php endif; ?>

    <div class="banner-content"
        style="background-image: url(<?php echo esc_url(LH_THEME_URL . '/assets/images/vector.png') ?>);">
        <?php if (!empty($content[0]['heading']) || !empty($content[0]['description'])) : ?>
        <div class="banner-info">
            <?php if (!empty($content[0]['heading'])) : ?>
            <h2 class="banner-heading"><?php echo esc_html($content[0]['heading']); ?></h2>
            <?php endif; ?>
            <?php if (!empty($content[0]['description'])) : ?>
            <p class="banner-description"><?php echo esc_html($content[0]['description']); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($links)) : ?>
        <div class="banner-links">
            <?php foreach ($links as $link) :
                    $url = $link['link']['url'] ?? '';
                    $title = $link['link']['title'] ?? '';
                    $target = $link['link']['target'] ?? '_self';
                    $bg_color = $link['background_color'];
                    if ($url && $title): ?>
            <a style="background-color: <?php echo esc_attr($bg_color) ?>;" class="banner-link"
                href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
                <?php echo esc_html($title); ?>
            </a>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php

if (empty($items)) {
    return;
}

?><ul class="social-media__menu">
    <?php foreach ($items as $index => $media) : ?>
        <li class="social-media__item social-media--<?= esc_attr($media['icon']); ?>">
            <?php if (!empty($media['link']['url'])) : ?>
                <a href="<?= esc_url($media['link']['url']) ?>" class="social-media__link" <?= origin_target_attr($media['link']); ?>>
            <?php endif; ?>
                <span class="sr"><?= $media['link']['title'] ?: $media['icon'] ?></span>
                <?php locate_template(sprintf('/assets/images/icons/%s.svg', $media['icon']), true, false); ?>
            <?php if (!empty($media['link']['url'])) : ?>
                </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

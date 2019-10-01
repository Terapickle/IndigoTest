<?php

// Set the content width for oEmbeds.
//
if (!isset($content_width)) {
    $content_width = 870;
}

// Setup theme support etc.
//
add_action('after_setup_theme', function () {

    add_image_size('card-thumb', 722, 430, true);

    add_theme_support('origin-developer', ['version' => '2.5']);
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);

});

// Register new Theme Options
//
origin()->singleton(\Origin\Theme\Settings::class, \Origin\Theme\Settings::class);
origin()->make(\Origin\Theme\Settings::class);

// Register the "shortcode documentation"
//
origin()->singleton(\Origin\Theme\ShortcodeDocumentation::class, \Origin\Theme\ShortcodeDocumentation::class);
origin()->make(\Origin\Theme\ShortcodeDocumentation::class);

// Register the "archive selector"
//
origin()->singleton(\Origin\Theme\ArchiveSelector::class, \Origin\Theme\ArchiveSelector::class);
origin()->make(\Origin\Theme\ArchiveSelector::class);

// Register WordPress navigation menus.
//
add_action('init', function () {

    register_nav_menus([
        'primary' => 'Primary',
        'footer-copyright' => 'Footer (Copyright)'
    ]);

});

// Register WordPress sidebars
//
add_action('init', function () {

    register_sidebar([
        'id'            => 'primary',
        'name'          => 'Primary',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>'
    ]);

    register_sidebars(3, [
        'id'            => 'footer',
        'name'          => 'Footer %d',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>'
    ]);

});

// Automatically register WordPress Meta boxes
//
foreach (glob(ORIGIN_PATH . '/theme/Metaboxes/*.php') as $metabox) {

    $name = substr(basename($metabox), 0, -4);

    if ($name === 'Metabox') {
        continue;
    }

    origin()->make("Origin\\Theme\\Metaboxes\\{$name}");
}

// Automatically register WordPress Blocks
//
foreach (glob(ORIGIN_PATH . '/theme/Blocks/*.php') as $block) {

    $name = substr(basename($block), 0, -4);

    if ($name === 'Block') {
        continue;
    }

    origin()->make("Origin\\Theme\\Blocks\\{$name}");
}

// Automatically register WordPress Shortcodes
//
foreach (glob(ORIGIN_PATH . '/theme/Shortcodes/*.php') as $shortcode) {

    $name = substr(basename($shortcode), 0, -4);

    if ($name === 'Shortcode') {
        continue;
    }

    $_shortcode = origin()->make("Origin\\Theme\\Shortcodes\\{$name}");

    add_shortcode($_shortcode->tag(), [$_shortcode, 'register']);
}

// Automatically register WordPress Widgets
//
add_action('widgets_init', function () {

    foreach (glob(ORIGIN_PATH . '/theme/Widgets/*.php') as $widget) {

        $name = substr(basename($widget), 0, -4);

        if ($name === 'Widget') {
            continue;
        }

        register_widget("Origin\\Theme\\Widgets\\{$name}");
    }

});

// Automatically register Taxonomies
//
foreach (glob(ORIGIN_PATH . '/theme/Taxonomies/*.php') as $types) {

    $name = substr(basename($types), 0, -4);

    if ($name === 'Taxonomy') {
        continue;
    }

    origin()->make("Origin\\Theme\\Taxonomies\\{$name}")->init();
}

// Automatically register PostTypes
//
foreach (glob(ORIGIN_PATH . '/theme/PostTypes/*.php') as $types) {

    $name = substr(basename($types), 0, -4);

    if ($name === 'PostType') {
        continue;
    }

    origin()->make("Origin\\Theme\\PostTypes\\{$name}")->init();
}

// Enqueue our scripts
//
add_action('wp_enqueue_scripts', function () {

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    $js = '/assets/dist/main.js';
    wp_enqueue_script('origin', get_theme_file_uri($js), ['jquery'], filemtime(get_theme_file_path($js)));

    wp_localize_script('origin', 'origin', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);

});

// Enqueue our stylesheet(s)
//
add_action('wp_enqueue_scripts', function () {

    $css = '/assets/dist/main.css';
    wp_enqueue_style('origin', get_theme_file_uri($css), null, filemtime(get_theme_file_path($css)));

}, 25);

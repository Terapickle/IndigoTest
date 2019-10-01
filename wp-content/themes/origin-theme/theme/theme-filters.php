<?php

/**
 * Turn off ACF settings screens for non-admins
 */
add_action('acf/settings/show_admin', function () {
    return current_user_can('manage_options');
});

/**
 * Force Yoast metabox to have lowest priority
 */
add_filter('wpseo_metabox_prio', function () {
    return 'low';
});

// Set the <meta name="theme-color" /> and manifest theme color
//
add_filter('origin_manifest_theme_color', function ($color) {
    if (!$config = origin_get_asset_config()) {
        return $color;
    }
    return $config->colors->beige ?: $color;
});

/**
 * Hook a "skip to link" into the footer somewhere
 *
 * @return void
 */
add_action('wp_footer', function () {
    ?><a href="#main" tabindex="1" class="skip-link">Skip to content</a><?php
});

// Add Shortcode support for Widget Text/Title inputs
//
add_filter('widget_title', 'do_shortcode');
add_filter('widget_text', 'do_shortcode');

/**
 * Add oembed responsive support, basically just wraps
 * the embed in .responsive-video.
 *
 * @param $html
 * @param $url
 * @param $attr
 * @return string
 */
add_filter('embed_oembed_html', function ($html, $url, $attr) {
    return '<div class="responsive-embed responsive-embed--16:9">'.trim($html).'</div>';
}, 1, 3);

add_filter('acf/format_value/type=oembed', function ($value, $post_id, $field) {
    if (0 !== mb_strpos($value, '<iframe')) {
        return $value;
    }
    return '<div class="responsive-embed responsive-embed--16:9">'.trim($value).'</div>';
}, 15, 3);

// Update archive title on "home" aka "blog" pages
//
add_filter('get_the_archive_title', function ($title) {
    if (is_home() && get_option('page_for_posts')) {
        return get_the_title(get_option('page_for_posts'));
    } elseif (is_search()) {
        return sprintf('Search: %s', get_search_query());
    } elseif (is_post_type_archive() && 0 === mb_strpos($title, 'Archives: ')) {
        return mb_substr($title, 10);
    } elseif (is_category() || is_tag()) {
        return single_cat_title('', false);
    } elseif (is_author()) {
        return '<span class="vcard">' . get_the_author() . '</span>';
    }
    return $title;
});

/**
 * Remove title="" attribute from nav menu if same as text
 */
add_filter('nav_menu_link_attributes', function ($atts, $item, $args, $depth) {
    if ($item->title == $atts['title']) {
        unset($atts['title']);
    }
    return $atts;
}, 10, 4);

// Stop figure having inline style width that break the design!!!
//
add_filter( 'img_caption_shortcode_width', '__return_false' );


// Automatically highlight the post type archive menu item
//
add_filter('nav_menu_css_class', function ($classes, $item, $args, $depth) {

    if (!is_post_type_archive() && !is_singular()) {
        return $classes;
    }

    $url = untrailingslashit($item->url);
    $o = get_queried_object();

    $archive = is_singular() ? $o->post_type : $o->name;

     if (untrailingslashit(get_post_type_archive_link($archive)) == $url) {
        $classes[] = 'active';
    }

    return $classes;

}, 10, 4);

// Remove /page/1 from pagination to prevent redirect
// 
add_filter('paginate_links', function ($link) {
    return preg_replace('#page\/1[^\d]#', '', $link);
});

/**
 * Add brand colors to the mce editor
 */
add_filter('tiny_mce_before_init', function ($data) {
    if (!$config = origin_get_asset_config()) {
        return;
    }
    
    $colors = [];

    foreach ($config->colors as $k => $v) {
        $v = 0 === mb_strpos($v, '#') ? mb_substr($v, 1) : $v;
        $colors[] = "\"{$v}\", \"$k\",";
    }

    $data['textcolor_map'] = '['.implode(PHP_EOL, $colors).']';
    $data['textcolor_rows'] = 2;

    return $data;
});

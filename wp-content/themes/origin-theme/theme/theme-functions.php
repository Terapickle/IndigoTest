<?php

if (!function_exists('origin')) {
    /**
     * Helper function, grab a copy of the framework.
     *
     * @param null $make
     * @return \Illuminate\Container\Container
     */
    function origin($make = null)
    {
        if (is_null($make)) {
            return \Illuminate\Container\Container::getInstance();
        }

        return \Illuminate\Container\Container::getInstance()->make($make);
    }
}

if (!function_exists('origin_cache')) {
    /**
     * Helper funciton to decrease amount of transient boilerplate
     * code needed for basic transient work.
     *
     * @param mixed $key - maximum 40 characters
     * @param int $time - in minutes before expiration
     * @param $callback
     *
     * @return mixed
     */
    function origin_cache($key, $time, $callback)
    {
        if ($return = get_transient($key)) {
            return $return;
        }

        $return = $callback();
        set_transient($key, $return, MINUTE_IN_SECONDS * intval($time));

        return $return;
    }
}

if (!function_exists('dd')) {
    /**
     * Dump some data, then exit the script
     *
     * @param mixed [...]
     */
    function dd(...$params)
    {
        array_map(function ($a) {
            echo '<pre>';
            var_dump($a);
            echo '</pre>';
        }, $params);

        exit;
    }
}

if (!function_exists('origin_posts')) {
    /**
     * Helper function to wrap WP_Query calls
     *
     * @param array $arguments
     * @param $callback
     * @param boolean $echo
     * @return void|string
     */
    function origin_posts($arguments, $callback, $echo = true)
    {
        $q = new WP_Query($arguments);

        ob_start();

        while ($q->have_posts()) : $q->the_post();
            $callback();
        endwhile;

        wp_reset_postdata();

        $c = ob_get_contents();
        ob_end_clean();

        if (!$echo) {
            return $c;
        }

        echo $c;
    }
}

if (!function_exists('returns')) {
    /**
     * Return a value through a function
     *
     * @param mixed $value
     * @return function
     */
    function returns($value) {
        return function () use ($value) {
            return $value;
        };
    }
}

if (!function_exists('echos')) {
    /**
     * Echo Value through a function
     *
     * @param mixed $value
     * @return function
     */
    function echos($value) {
        return function () use ($value) {
            echo $value;
        };
    }
}

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using dot notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function array_get($array, $get, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) or !array_key_exists($segment, $array)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }
}

if (!function_exists('origin_archive_pagination')) {
    /**
     * Archive pagination
     */
    function origin_archive_pagination()
    {
        global $wp_query;

        if ($wp_query->max_num_pages < 2) {
            return;
        }

        $big = 999999999;

        $links = paginate_links([
            'base'    => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'  => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total'   => $wp_query->max_num_pages,
            'type'    => 'array',
        ]);

        origin_view('pagination', compact('links'));
    }
}

if (!function_exists('origin_theme_copyright_default')) {
    /**
     * Return the default message for the themes copyright
     *
     * @return string
     */
    function origin_theme_copyright_default()
    {
        return 'Copyright %1$d %2$s.';
    }
}

if (!function_exists('origin_theme_copyright')) {
    /**
     * Output the themes "copyright"
     *
     * @return string
     */
    function origin_theme_copyright()
    {
        $format = get_field('copyright_text', 'option') ?: origin_theme_copyright_default();

        return '&copy; '.sprintf($format, current_time('Y'), get_bloginfo('name'));
    }
}

if (!function_exists('origin_theme_credit')) {
    /**
     * Output the themes "credit" line, typically found in the footer
     *
     * @return string
     */
    function origin_theme_credit()
    {
        $website = apply_filters('origin_theme_author_credit_url', 'https://indigotree.co.uk');
        $name = apply_filters('origin_theme_author_credit_name', 'Indigo Tree');

        return 'Built by <a href="'.esc_url($website).'" target="_blank" rel="noopener">'.$name.'</a>';
    }
}

if (!function_exists('origin_target_attr')) {
    /**
     * Helper function to add the target attribute to an anchor tag
     *
     * @param array $link
     * @return string
     */
    function origin_target_attr ($link = [])
    {
        if (empty($link)) {
            return '';
        }

        $rel = !empty($link['target']) && $link['target'] === '_blank' ? 'rel="noopenner noreferrer" ' : '';

        return !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '" ' . $rel : '';
    }
}

if (!function_exists('origin_view')) {
    /**
     * Output a theme partial/view from the `views/*` dir
     *
     * @param string $path
     * @param array $data
     * @return void
     */
    function origin_view($path, array $data = [])
    {
        $__template_path = locate_template("views/{$path}.php");

        if (is_array($data) && count($data) > 0) {
            extract($data);
        }

        if ($__template_path) {
            include ($__template_path);
        }
    }
}

if (!function_exists('origin_get_archive_page')) {
    /**
     * Return the page attached to the given post type archive
     * 
     * @param $type mixed
     * @return object|null
     */
    function origin_get_archive_page($type = null)
    {
        return origin(\Origin\Theme\ArchiveSelector::class)->getPage($type);
    }
}

if (!function_exists('origin_get_asset_config')) {
    /**
     * Get asset configuration data 
     *
     * @return object
     */
    function origin_get_asset_config()
    {
        if (!file_exists(ORIGIN_PATH.'/assets/config.json')) {
            return $color;
        }

        return json_decode(file_get_contents(ORIGIN_PATH.'/assets/config.json'));
    }
}

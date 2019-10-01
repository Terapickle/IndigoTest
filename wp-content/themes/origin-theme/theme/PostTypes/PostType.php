<?php

namespace Origin\Theme\PostTypes;

abstract class PostType
{
    /**
     * Setup the post type
     *
     * @return void
     */
    public function init()
    {
        add_action('init', [$this, 'register']);

        if (method_exists($this, 'columns')) {
            add_filter('manage_edit-'.$this->type.'_columns', [$this, 'columns']);
        }

        if (method_exists($this, 'renderColumns')) {
            add_action('manage_'.$this->type.'_posts_custom_column', [$this, 'renderColumns'], 10, 2);
        }
    }

    /**
     * Register the post type with WordPress
     *
     * @param array $args
     *
     * @return void
     */
    protected function registerPostType(array $args = [])
    {
        $defaults = [
            'public'   => true,
            'labels'   => $this->generateLabels(),
            'supports' => ['title', 'editor', 'excerpt', 'author', 'thumbnail'],
            'rewrite'  => [
                'slug' => $this->slug,
            ],
        ];
        
        $args = wp_parse_args($args, $defaults);

        if (isset($args['has_archive']) && $args['has_archive'] === true) {
            $args['has_archive'] = $this->getArchiveUrl();
        }
        
        register_post_type($this->type, $args);
    }

    /**
     * Determine the Archive URL from the connected page
     *
     * @return string|boolean
     */
    protected function getArchiveUrl()
    {
        $archive = origin_get_archive_page($this->type);

        return is_a($archive, 'WP_Post') ? get_page_uri($archive->ID) : true;
    }

    /**
     * Automatically generate labels
     *
     * @return array
     */
    protected function generateLabels() : array
    {
        $plural = $this->name;

        if (empty($this->plural)) {
            $plural = \Doctrine\Common\Inflector\Inflector::pluralize($this->name);
        }

        return [
            'name'               => $plural,
            'singular_name'      => $this->name,
            'add_new'            => __('Add New'),
            'add_new_item'       => sprintf('Add New %s', $this->name),
            'edit_item'          => sprintf('Edit %s', $this->name),
            'new_item'           => sprintf('New %s', $this->name),
            'all_items'          => sprintf('All %s', $plural),
            'view_item'          => sprintf('View %s', $this->name),
            'search_items'       => sprintf('Search %s', $plural),
            'not_found'          => sprintf('No %s found', strtolower($plural)),
            'not_found_in_trash' => sprintf('No %s found in trash', strtolower($plural)),
            'parent_item_colon'  => '',
            'menu_name'          => $plural
        ];
    }
}

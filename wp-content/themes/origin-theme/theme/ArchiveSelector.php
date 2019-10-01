<?php

namespace Origin\Theme;

class ArchiveSelector
{
    /**
     * Prefix used for the settings
     * 
     * @var string
     */
    protected $prefix = 'archive_page_';

    /**
     * Create a new Archive Selector
     *
     * @return void
     */
    public function __construct()
    {
        add_filter('display_post_states', [$this, 'addPostStates'], 10, 2);

        add_action('origin_register_settings', [$this, 'createSettingsTab']);

        add_action('acf/save_post', [$this, 'setSaveRewriteTransient'], 15);

        add_action('init', [$this, 'flushRewriteRules'], 1000);

        add_action('admin_bar_menu', [$this, 'addMenuToAdminBar'], 80);
    }

    /**
     * Create a tab in the theme-options
     * 
     * @return void
     */
    public function createSettingsTab()
    {
        $settings = origin()->make(\Origin\Theme\Settings::class);
        $fields = [];

        foreach ($this->getPostTypes() as $type) {
            $fields[] = [
                'key' => 'field_'.md5(sprintf('archive_page_%s', $type->name)),
                'name' => "{$this->prefix}{$type->name}",
                'label' => $type->labels->singular_name,
                'type' => 'post_object',
                'post_type' => ['page'],
                'allow_null' => 1,
                'return_format' => 'object',
            ];
        }

        if (sizeof($fields) > 0) {
            $settings->group('settings_tab_archive_pages', 'Archive Pages', $fields);
        }
    }

    /**
     * Add "post states" next to the title in the admin area
     * 
     * @param array $states
     * @param $post
     *
     * @return array
     */
    public function addPostStates(array $states, $post) : array
    {
        foreach ($this->getPostTypes() as $type) {
            $value = get_field("{$this->prefix}{$type->name}", 'option');
            if ($value && $value->ID === $post->ID) {
                $states[] = sprintf('%s Archive', $type->labels->singular_name);
            }
        }

        return $states;
    }

    /**
     * Return all post types that have archive pages
     * 
     * @return array
     */
    protected function getPostTypes() : array
    {
        $types = get_post_types(['public' => true, '_builtin' => false], 'objects');
        $return = [];

        foreach ($types as $type) {
            if ($type->has_archive) {
                $return[] = $type;
            }
        }

        return $return;
    }

    /**
     * Return the page attached to the given post type archive
     * 
     * @param $type
     * @return object|null
     */
    public function getPage($type = null)
    {
        if (is_null($type) && is_post_type_archive()) {
            $type = get_queried_object()->name;
        }
        
        if (is_object($type) && property_exists($type, 'name')) {
            $type = $type->name;
        }

        $return = get_field("{$this->prefix}{$type}", 'option');

        if (!is_a($return, 'WP_Post') && is_numeric($return)) {
            return get_page($return);
        }

        return $return;
    }

    /**
     * Save temporary data to tell WordPress the clear rewrite rules
     *
     * @param mixed $type
     * 
     * @return void
     */
    public function setSaveRewriteTransient($type) : void
    {
        if ('options' === $type) {
            set_transient('origin_updated_archive_page_options', true, 15 * MINUTE_IN_SECONDS);
        }
    }

    /**
     * Clear rewrite rules if previously set to do so
     *
     * @return void
     */
    public function flushRewriteRules() : void
    {
        if (get_transient('origin_updated_archive_page_options')) {
            flush_rewrite_rules();
            delete_transient('origin_updated_archive_page_options');
        }
    }

    /**
     * Add an "edit page" link to the admin bar
     *
     * @param $wp_admin_bar
     *
     * @return void
     */
    public function addMenuToAdminBar($wp_admin_bar) : void
    {
        if (!is_post_type_archive()) {
            return;
        }

        $type = get_queried_object();

        if (!is_a($type, 'WP_Post_Type')) {
            return;
        }

        if (!$archive = origin_get_archive_page($type->name)) {
            return;
        }

        if (!current_user_can('edit_post', $archive->ID)) {
            return;
        }

        $wp_admin_bar->add_menu([
            'id' => 'edit',
            'title' => get_post_type_object('page')->labels->edit_item,
            'href' => get_edit_post_link($archive->ID),
        ]);
    }
}

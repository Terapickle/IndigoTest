<?php

namespace Origin\Theme;

class Settings
{
    /**
     * Theme options key.
     *
     * @var string
     */
    protected $key = 'theme-options';

    /**
     * Collection of groups used for tabs.
     *
     * @var array
     */
    protected $groups = [];

    /**
     * Create some new settings fields/sections.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('init', [$this, 'register'], 15);
        add_action('admin_bar_menu', [$this, 'bar'], 99);
    }

    public function register()
    {
        if (!function_exists('acf_add_options_sub_page') || !function_exists('acf_add_local_field_group')) {
            return;
        }

        $this->menu();
        $this->settings();

        do_action('origin_register_settings');

        $this->wrapper("{$this->key}_wrapper", 'Settings', $this->groups);
    }

    /**
     * Register various fields for the theme options screen.
     *
     * @return array
     */
    protected function settings()
    {
        $this->group('social', 'Social Media', [
            [
                'key'        => 'social_media',
                'name'       => 'social_media',
                'label'      => 'Social Media',
                'type'       => 'repeater',
                'sub_fields' => [
                    [
                        'key'     => 'social_icon',
                        'name'    => 'icon',
                        'label'   => 'Icon',
                        'type'    => 'select',
                        'choices' => [
                            'facebook'  => 'Facebook',
                            'twitter'   => 'Twitter',
                            'pinterest' => 'Pinterest',
                            'linkedin'  => 'Linkedin',
                            'instagram' => 'Instagram',
                            'youtube'   => 'Youtube'
                        ]
                    ],
                    [
                        'key'   => 'social_link',
                        'name'  => 'link',
                        'label' => 'Link',
                        'type'  => 'link'
                    ]
                ]
            ]
        ]);

        $this->group('footer', 'Footer', [
            [
                'key'          => 'copyright_text',
                'name'         => 'copyright_text',
                'label'        => 'Copyright Text',
                'type'         => 'text',
                'placeholder'  => origin_theme_copyright_default(),
                'instructions' => '<code>%1$d</code> = Current Year, <code>%2$s</code> = Site Name'
            ]
        ]);

        $this->group('page-404', '404 Page', [
            [
                'key'         => 'page_404_title',
                'name'        => 'page_404_title',
                'label'       => 'Title',
                'type'        => 'text',
                'placeholder' => 'Page Not Found'
            ],
            [
                'key'   => 'page_404_content',
                'name'  => 'page_404_content',
                'label' => 'Content',
                'type'  => 'wysiwyg'
            ]
        ]);
    }

    public function group($key, $label, $fields)
    {
        $this->groups[] = [
            'key'       => "settings_tab_${key}",
            'label'     => $label,
            'type'      => 'tab',
            'placement' => 'left'
        ];

        foreach ($fields as $field) {
            $this->groups[] = $field;
        }
    }

    protected function wrapper($key, $label, $fields)
    {
        $return = [
            'key'      => $key,
            'title'    => $label,
            'fields'   => $fields,
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => $this->key,
                    ]
                ]
            ]
        ];

        acf_add_local_field_group($return);

        return $return;
    }

    protected function menu()
    {
        acf_add_options_sub_page([
            'page_title'  => 'Theme Options',
            'menu_title'  => 'Theme Options',
            'parent_slug' => 'themes.php',
            'capability'  => 'edit_theme_options',
            'menu_slug'   => $this->key
        ]);
    }

    /**
     * Register the WordPress admin bar menu item
     * used for easy access to the settings
     *
     * @return void
     */
    public function bar($bar)
    {
        $bar->add_node([
            'id'     => 'theme-options',
            'title'  => 'Theme Options',
            'href'   => admin_url('themes.php?page='.$this->key),
            'parent' => 'site-name'
        ]);
    }
}
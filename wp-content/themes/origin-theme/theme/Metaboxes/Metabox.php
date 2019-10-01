<?php

namespace Origin\Theme\Metaboxes;

abstract class Metabox
{
    public function __construct()
    {
        add_action('acf/init', [$this, 'register']);
    }

    public function register()
    {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }
        
        acf_add_local_field_group($this->settings());
    }
}

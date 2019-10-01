<?php

namespace Origin\Theme\Blocks;

abstract class Block
{
    public function __construct()
    {
        add_action('acf/init', [$this, 'register']);
    }

    public function register()
    {
        if (!function_exists('acf_register_block') || !function_exists('acf_add_local_field_group')) {
            return;
        }
        
        $settings = $this->block();

        if (!isset($settings['render_callback'])) {
            $settings['render_callback'] = function ($acf) {
                echo $this->callback($acf);
            };
        }

        acf_register_block($settings);
        acf_add_local_field_group($this->settings());
    }

    public function render()
    {
        echo 'This block has no output';
    }
}

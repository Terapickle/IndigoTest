<?php

namespace Origin\Theme\Shortcodes;

/**
 * Output social media icons as defined in Theme Options
 */
class SocialShortcode extends Shortcode
{
    /**
     * The unique shortcode tag used with add_shortcode();.
     *
     * @var string
     */
    protected $tag = 'social';

    /**
     * Register field/attributes for this Shortcode.
     *
     * @return array
     */
    public function fields() : array
    {
        return [
            
        ];
    }

    /**
     * Shortcode callback. Renders the output for a Shortcode.
     *
     * @param array  $atts
     *
     * @return string
     */
    public function callback(array $atts = []) : string
    {
        ob_start();

            origin_view('social-media', [
                'items' => get_field('social_icons', 'option') ?? []
            ]);

            $c = ob_get_contents();

        ob_end_clean();

        return $c;
    }
}

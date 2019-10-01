<?php

namespace Origin\Theme\Shortcodes;

class WellShortcode extends Shortcode
{
    /**
     * The unique shortcode tag used with add_shortcode();.
     *
     * @var string
     */
    protected $tag = 'well';

    /**
     * Register field/attributes for this Shortcode.
     *
     * @return array
     */
    public function fields() : array
    {
        return [
            'type'   => 'primary',
            'border' => '',
            'size'   => 'md',
            'xclass' => '',
        ];
    }

    /**
     * Create the class="" attribute.
     *
     * @param array $atts
     *
     * @return string
     */
    protected function attrClass(array $atts = []) : string
    {
        $classes = ['well'];

        if (!empty($atts['type'])) {
            $classes[] = 'well--'.$atts['type'];
        }

        if (!empty($atts['size'])) {
            $classes[] = 'well--'.$atts['size'];
        }

        if (!empty($atts['border']) && ($atts['border'] != 'no' || $atts['border'] != '0' || $atts['border'] != false)) {
            $classes[] = 'well--bordered';
        }

        if (!empty($atts['xclass'])) {
            $classes[] = $atts['xclass'];
        }

        return sprintf(' class="%s"', esc_attr(implode(' ', $classes)));
    }

    /**
     * Shortcode callback. Renders the output for a Shortcode.
     *
     * @param array  $atts
     * @param string $content
     *
     * @return string
     */
    public function callback(array $atts = [], string $content = '') : string
    {
        $classes = $this->attrClass($atts);

        return '<div'.$classes.'>'.do_shortcode($content).'</div>';
    }
}

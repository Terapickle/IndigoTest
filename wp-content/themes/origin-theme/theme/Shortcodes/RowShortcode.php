<?php

namespace Origin\Theme\Shortcodes;

class RowShortcode extends Shortcode
{
    /**
     * The unique shortcode tag used with add_shortcode();.
     *
     * @var string
     */
    protected $tag = 'row';

    /**
     * Register field/attributes for this Shortcode.
     *
     * @return array
     */
    public function fields() : array
    {
        return [
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
        $classes = ['row'];

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

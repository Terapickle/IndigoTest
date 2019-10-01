<?php

namespace Origin\Theme\Shortcodes;

class ColumnShortcode extends Shortcode
{
    /**
     * The unique shortcode tag used with add_shortcode();.
     *
     * @var string
     */
    protected $tag = 'column';

    /**
     * Register field/attributes for this Shortcode.
     *
     * @return array
     */
    public function fields() : array
    {
        return [
            'xclass'    => '',
            'xs'        => '',
            'sm'        => '',
            'md'        => '',
            'lg'        => '',
            'offset_xs' => '',
            'offset_sm' => '',
            'offset_md' => '',
            'offset_lg' => '',
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
        $classes = ['column'];

        if (!empty($atts['xclass'])) {
            $classes[] = $atts['xclass'];
        }

        foreach (['xs', 'sm', 'md', 'lg'] as $size) {
            if (!empty($atts[$size])) {
                $classes[] = "column--{$size}-{$atts[$size]}";
            }
        }

        foreach (['xs', 'sm', 'md', 'lg'] as $size) {
            if (!empty($atts["offset_{$size}"])) {
                $classes[] = "column--{$size}-offset-{$atts["offset_{$size}"]}";
            }
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

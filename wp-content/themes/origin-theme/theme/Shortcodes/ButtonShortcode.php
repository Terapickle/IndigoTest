<?php

namespace Origin\Theme\Shortcodes;

class ButtonShortcode extends Shortcode
{
    /**
     * The unique shortcode tag used with add_shortcode();.
     *
     * @var string
     */
    protected $tag = 'button';

    /**
     * Register field/attributes for this Shortcode.
     *
     * @return array
     */
    public function fields() : array
    {
        return [
            'xclass' => '',
            'size'   => 'md',
            'link'   => '',
            'target' => '',
            'type'   => 'primary'
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
        $classes = ['button'];

        if (!empty($atts['xclass'])) {
            $classes[] = $atts['xclass'];
        }

        if (!empty($atts['size'])) {
            $classes[] = "button--{$atts['size']}";
        }

        if (!empty($atts['type'])) {
            $classes[] = "button--{$atts['type']}";
        }

        return sprintf(' class="%s"', esc_attr(implode(' ', $classes)));
    }

    /**
     * Create the href="" and target attribute.
     *
     * @param array $atts
     *
     * @return string
     */
    protected function attrHref(array $atts = []) : string
    {
        $attributes = [];

        if (!empty($atts['link'])) {
            $attributes['href'] = esc_url($atts['link']);
        }

        if (!empty($atts['target'])) {
            $attributes['target'] = esc_attr($atts['target']);
        }

        if ($atts['target'] == '_blank') {
            $attributes['rel'] = 'noopener';
        }

        return implode(' ', array_map([$this, 'createAttr'], array_keys($attributes), $attributes));
    }

    /**
     * Returns an HTML attribute
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected function createAttr(string $key, string $value) : string
    {
        return esc_html($key).'="'.esc_attr($value).'"';
    }

    /**
     * Shortcode callback. Renders the output for a Shortcode.
     *
     * @param array  $atts
     * @param string $content
     *
     * @return string
     */
    public function callback(array $atts = [], $content = '') : string
    {
        $classes = $this->attrClass($atts);
        $href    = $this->attrHref($atts);

        return '<a '.$href.$classes.'>'.$content.'</a>';
    }
}

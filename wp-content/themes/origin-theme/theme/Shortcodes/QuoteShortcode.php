<?php

namespace Origin\Theme\Shortcodes;

class QuoteShortcode extends Shortcode
{
    /**
     * The unique shortcode tag used with add_shortcode();.
     *
     * @var string
     */
    protected $tag = 'quote';

    /**
     * Register field/attributes for this Shortcode.
     *
     * @return array
     */
    public function fields() : array
    {
        return [
            'type'   => 'primary',
            'cite'   => '',
            'icon'   => '1',
            'indent' => '',
            'image'  => '',
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
        $classes = ['quote'];

        if (!empty($atts['indent']) && $atts['indent'] == '1') {
            $classes[] = 'quote--indent';
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

        $quote = '<blockquote'.$classes.'>';

        if ($atts['icon'] && empty($atts['image'])) {
            $quote .= '<svg class="quote__icon" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 256h-80v-64c0-35.3 28.7-64 64-64h8c13.3 0 24-10.7 24-24V56c0-13.3-10.7-24-24-24h-8c-88.4 0-160 71.6-160 160v240c0 26.5 21.5 48 48 48h128c26.5 0 48-21.5 48-48V304c0-26.5-21.5-48-48-48zm-288 0H96v-64c0-35.3 28.7-64 64-64h8c13.3 0 24-10.7 24-24V56c0-13.3-10.7-24-24-24h-8C71.6 32 0 103.6 0 192v240c0 26.5 21.5 48 48 48h128c26.5 0 48-21.5 48-48V304c0-26.5-21.5-48-48-48z"></path></svg>';
        }

        if (!empty($atts['image'])) {
            $quote .= '<img class="quote__avatar" src="'.esc_url($atts['image']).'" alt="'.esc_attr('Photo for: '.$atts['cite']).'">';
        }

        $quote .= '<div class="quote__content">'.do_shortcode($content).'</div>';

        if (!empty($atts['cite'])) {
            $quote .= '<footer class="quote__footer">'.$atts['cite'].'</footer>';
        }

        $quote .= '</blockquote>';

        return $quote;
    }
}

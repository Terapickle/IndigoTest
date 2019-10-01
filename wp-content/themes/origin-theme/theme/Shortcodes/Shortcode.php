<?php

namespace Origin\Theme\Shortcodes;

abstract class Shortcode
{
    /**
     * A unique tag for this Shortcode.
     *
     * @var string
     */
    protected $tag;

    /**
     * Register field/attributes for this Shortcode.
     *
     * @return array
     */
    abstract public function fields();

    /**
     * Callback function to register with WordPress.
     *
     * @param array  $atts
     * @param string $content
     *
     * @return string
     */
    public function register($atts = [], $content = '')
    {
        return $this->callback(shortcode_atts($this->fields(), (array) $atts), (string) $content);
    }

    /**
     * Return the registered Shortcode tag.
     *
     * @return string
     */
    public function tag()
    {
        return $this->tag;
    }
}

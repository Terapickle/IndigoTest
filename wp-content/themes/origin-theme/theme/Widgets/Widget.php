<?php

namespace Origin\Theme\Widgets;

abstract class Widget extends \WP_Widget
{
    /**
     * A unique id/key for this Widget.
     *
     * @var string
     */
    protected $unique;

    /**
     * CSS class(es) added to the Widget
     *
     * @var string
     */
    protected $css_class;

    /**
     * Description of the widget.
     *
     * @var string
     */
    protected $description;

    /**
     * Create a new widget
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($this->unique, $this->title, [
            'classname'  => $this->css_class,
            'description' => $this->description
        ]);

        add_action('acf/init', [$this, 'register']);
    }

    public function register()
    {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group($this->settings());
    }

    /**
     * Update Widget options
     *
     * @param array $new
     * @param array $old
     * @return array
     */
    public function update($new, $old)
    {
        return $new;
    }

    /**
     * Register settings for this Widget.
     *
     * @return array
     */
    abstract protected function settings();

    /**
     * Widget form outputs fields dependant on your options
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        if (!$this->settings()) {
            echo '<p>There are no settings for this widget.</p>';
        }

        return $instance;
    }

    /**
     * Widget callback. Render the Widget content.
     *
     * @param $args
     * @param $instance
     * @return void
     */
    protected function callback($args, $instance)
    {
        echo '<pre>' . print_r($instance, true) . '</pre>';
    }

    /**
     * Standard WordPress widget() method. Alias for callback()
     *
     * @param $args
     * @param $instance
     * @return void
     */
    public function widget($args, $instance)
    {
        $this->callback(
            $args,
            $this->mapAcfFields($args, $instance)
        );
    }

    /**
     * Map over acf fields and apply get_fields
     *
     * @param array $args
     * @param array $instance
     * @return array
     */
    public function mapAcfFields ($args, $instance)
    {
        if (!isset($instance['acf'])) {
            return $instance;
        }

        $new = [];
        foreach ($instance['acf'] as $key => $value) {
            $new[$this->getKey($key)] = get_field($key, 'widget_' . $args['widget_id']);
        }
        $instance['acf'] = $new;
        return $instance;
    }

    protected function getKey($key) {
        $fields = $this->settings()['fields'];
        foreach ($fields as $field) {
            if ($key === $field['key']) {
                return $field['name'];
            }
        }
        return $key;
    }
}

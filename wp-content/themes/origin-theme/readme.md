# Origin Starter Theme

A flexible starter theme to rapidly speed up development for WordPress.

---

## Contents

* [Preface](#preface)
  - [Requirements (Production)](#requirements-production)
  - [Requirements (Development)](#requirements-development)
  - [Documentation](#documentation)
* [WP CLI](#wp-cli)
* [Custom Objects](#custom-objects)
  - [Metaboxes](#metaboxes)
  - [Shortcodes](#shortcodes)
  - [Widgets](#widgets)
  - [Blocks](#blocks)
  - [Post Types](#post-types)
  - [Taxonomies](#taxonomies)
* [Theme Options](#theme-options)
* [Views](#views)
* [Archive Selector](#archive-selector)
* [Frontend/Assets](#frontendassets)
  - [NPM Scripts](#npm-scripts)
  - [JSON Configuration](#json-configuration)
* [theme-\*.php files](#theme-php-files)
  - [theme-setup.php](#theme-setupphp)
  - [theme-filters.php](#theme-filtersphp)
  - [theme-functions.php](#theme-functionsphp)
* [Functions](#misc-functions)


## Preface

### Requirements (Production)

- PHP 7.2+.
- WordPress 5.2+

### Requirements (Development)

- PHP 7.2+
- WordPress 5.2+
- Node.js 8+
- Webpack
- Sass
- GIT
- Composer

### Documentation

Documentation is written in [Markdown](http://daringfireball.net/projects/markdown/syntax), any updates to the theme that require documentation should also be written in Markdown and included as part of this document.

## Installation

After downloading the theme, you will first need to ensure the theme name is `origin-theme`. It should exist within the themes directory here: `/wp-content/themes/origin-theme`.

Next you will need to run `composer install`, and `npm install` to install PHP and JavaScript dependencies. Without these, the theme will not function correctly, and you will not be able to activate it.

You can then run `npm start` to start watching and building the theme assets. With all of these steps run successfully, you can activate the theme.

## Custom Fields

Custom fields are powered by [Advanced Custom Fields (ACF) Pro](https://www.advancedcustomfields.com/).

Please note, we disable the interface for ACF for non admins, as all fields are generated via PHP. We do not support custom fields created & served via the UI.

We recommend you create fields via the UI, and then export them as PHP through the ACF tools area. This will export your field(s) in an array format that can be copied into the relevant part of the theme. Please note, that they `key` attribute MUST be unique across every field in your project.

## WP CLI

Although the theme will work without WP CLI, we assume you're using it. There are a number of commands available with WP CLI that will allow you build certain elements faster.

* * *

## Building with Origin

### Metaboxes

Metaboxes can be created with WP CLI using the command:

```
wp origin metabox {name} --field="{fields}"
```

You can also create Metaboxes manually by creating a class in the appropriate folder. For example, to create an `Event` Metabox for the `event` Post Type you must create a file within `/theme/Metaboxes` called `EventMetaBox.php` containing the following code:

```php
<?php

namespace Origin\Theme\Metaboxes;

class EventMetaBox extends Metabox
{

    /**
     * A unique id/key for this Metabox.
     *
     * @var string
     */
    protected $unique = 'event-metabox';
    
    /**
     * The "name" or "title" of the Metabox.
     *
     * @var string
     */
    protected $title = 'Event';

    /**
     * Register settings for this Metabox
     *
     * @return array
     */
    protected function settings() : array
    {
        return [
            'key' => $this->unique,
            'title' => $this->title,
            'fields' => [
                [
                    'key' => 'field_590062b9bcfb8',
                    'label' => 'Starts At',
                    'name' => 'event_starts_at',
                    'type' => 'date_time_picker',
                    'display_format' => 'd/m/Y g:i a',
                    'return_format' => 'Y-m-d H:i:s',
                ],
                [
                    'key' => 'field_590062f1bcfb9',
                    'label' => 'Ends At',
                    'name' => 'events_ends_at',
                    'type' => 'date_time_picker',
                    'display_format' => 'd/m/Y g:i a',
                    'return_format' => 'Y-m-d H:i:s',
                ]
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'event'
                    ]
                ]
            ]
        ];
    }
}
```

### Shortcodes

Shortcodes can be created with WP CLI using the command:

```
wp origin shortcode {name} --fields="{fields}"
```

You can also create Shortcodes manually by creating a class in the appropriate folder. For example, to create a `LeadShortcode` Shortcode with the tag `[lead]` you must create a file within `/theme/Shortcodes` called `LeadShortcode.php` containing the following code:

```php
<?php

namespace Origin\Theme\Shortcodes;

class LeadShortcode extends Shortcode
{
    /**
     * The unique shortcode tag used with add_shortcode();.
     *
     * @var string
     */
    protected $tag = 'lead';

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
        $classes = ['lead'];

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

        return '<p'.$classes.'>'.do_shortcode($content).'</p>';
    }
}
```

### Widgets

Widgets can be created with WP CLI using the command:

```
wp origin widget {name} --fields="{fields}"
```

You can also create Widgets manually by creating a class in the appropriate folder. For example, to create a `ImageWidget` Widget you must create a file within `/theme/Widgets` containing the following code:

```php
<?php

namespace Origin\Theme\Widgets;

class ImageWidget extends Widget
{
    /**
     * A unique id/key for this Widget.
     *
     * @var string
     */
    protected $unique = 'image-widget';

    /**
     * The "name" or "title" of the widget.
     *
     * @var string
     */
    protected $title = '+ Image';

    /**
     * CSS class(es) added to the Widget
     *
     * @var string
     */
    protected $css_class = 'image-widget';

    /**
     * Register fields for this Widget.
     *
     * @return array
     */
    protected function settings() : array
    {
        return [
            'key'      => $this->unique,
            'title'    => $this->title,
            'fields'   => [
                [
                    'key'   => 'title',
                    'name'  => 'title',
                    'type'  => 'text',
                    'label' => 'Title'
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'widget',
                        'operator' => '==',
                        'value'    => $this->unique
                    ]
                ]
            ]
        ];
    }

    /**
     * Widget callback. Render the Widget content.
     *
     * @param $args
     * @param $instance
     * @return void
     */
    protected function callback(array $args, array $instance) : void
    {
        $title = apply_filters('widget_title', $instance['acf']['title'], $instance, $this->id_base);

        echo $args['before_widget'];

        echo $title ? $args['before_title'] . $title . $args['after_title'] : '';

        // ..

        echo $args['after_widget'];
    }
}
```

### Blocks

Blocks can be created with WP CLI using the command:

```
wp origin block {name} --fields="{fields}"
```

You can also create Blocks manually by creating a class in the appropriate folder. For example, to create a `ImageBlock` Block you must create a file within `/theme/Blocks` containing the following code:

```php
<?php

namespace Origin\Theme\Blocks;

class ImageBlock extends Block
{
    /**
     * A unique id/key for this Block.
     *
     * @var string
     */
    protected $unique = 'image-block';
    
    /**
     * The "name" or "title" of the Block.
     *
     * @var string
     */
    protected $title = 'Image';

    /**
     * Register settings for this Block
     *
     * @return array
     */
    protected function block() : array
    {
        return [
            'name'     => $this->unique,
            'title'    => $this->title,
            'category' => 'formatting',
            'icon'     => 'screenoptions',
            'keywords' => []
        ];
    }

    /**
     * Render this Block
     *
     * @param array $acf
     * @return string
     */
    public function callback(array $acf) : string
    {
        return print_r($acf, true);
    }

    /**
     * Register settings for this Blocks fields
     *
     * @return array
     */
    protected function settings() : array
    {
        return [
            'key'      => "{$this->unique}-group",
            'title'    => $this->title,
            'fields'   => [
                
            ],
            'location' => [
                [
                    [
                        'param'    => 'block',
                        'operator' => '==',
                        'value'    => "acf/{$this->unique}"
                    ]
                ]
            ]
        ];
    }
}
```

### Post Types

Post Types can be created with WP CLI using the command:

```
wp origin posttype {name}
```

You can also create Post Types manually by creating a class in the appropriate folder. For example, to create an `Event` Post Type you must create a file within `/theme/PostTypes` called `Event.php` containing the following code:

```php
<?php

namespace Origin\Theme\PostTypes;

class Event extends PostType
{
    /**
     * The unique key
     * 
     * @var string
     */
    protected $type = 'event';

    /**
     * The slug used in the rewrite url
     * 
     * @var string
     */
    protected $slug = 'events';

    /**
     * The readable name
     * 
     * @var string
     */
    protected $name = 'Event';

    /**
     * Register the post type
     * 
     * @return void
     */
    public function register()
    {
        $this->registerPostType([
            'menu_icon' => 'dashicons-calendar',
            'has_archive' => true,
        ]);
    }
}
```

### Taxonomies

Taxonomies can be created with WP CLI using the command:

```
wp origin taxonomy {name}
```

You can also create Taxonomies manually by creating a class in the appropriate folder. For example, to create a `Type` Taxonomy for an `Event` PostType you must create a file within `/theme/Taxonomies` called `Type.php` containing the following code:

```php
<?php

namespace Origin\Theme\Taxonomies;

class Type extends Taxonomy
{
    /**
     * The unique key
     * 
     * @var string
     */
    protected $taxonomy = 'type';

    /**
     * The slug used in the rewrite url
     * 
     * @var string
     */
    protected $slug = 'type';

    /**
     * The slug used in the rewrite url
     * 
     * @var string
     */
    protected $postTypes = ['event'];

    /**
     * The readable name
     * 
     * @var string
     */
    protected $name = 'Event Type';

    /**
     * Register the post type
     * 
     * @return void
     */
    public function register()
    {
        $this->registerTaxonomy([
            'hierarchical' => true,
        ]);
    }
}
```

* * *

## Theme Options

Theme options are stored within `/theme/Settings.php` and must be registered within the `settings()` method. An example grouping can be seen below. This will create 2 fields (Phone Number & Email Address) which will exist within it's own "page".

```php
$this->group('contact', 'Contact Information', [
    [
        'key' => 'contact_phone',
        'name' => 'contact_phone',
        'label' => 'Phone Number',
        'type' => 'text',
    ],
    [
        'key' => 'contact_email',
        'name' => 'contact_email',
        'label' => 'E-Mail Address',
        'type' => 'text',
    ]
]);
```

## Views

Origin has the idea of "Views". These are reusable snippets of HTML that are decoupled from their content. The idea is that a view contains no "database" code. They must only contain presentational code.

Views exist within the `/views` directory, and consist of simple PHP files. For example, if you were to create a view to render a "card" you would create `/views/card.php` and include the following:

```php
<article class="card">
    <?php if (!empty($image)) : ?>
        <img src="<?= esc_url($image);" alt />
    <?php endif; ?>
    <h2><?= $title; ?></h2>
    <?= wpautop($content); ?>
</article>
```

This is a "dumb" component. It expects data to be given to it, rather than querying for it itself. This allows us to do something like this:

```php
origin_view('card', [
    'image' => 'https://example.com/image.png',
    'title' => 'This Is The Title',
    'content' => 'This is the excerpt, you can imagine it longer.'
]);
```

Or like this:

```php
origin_view('card', [
    'image' => get_field('card_thumbnail'),
    'title' => get_the_title(),
    'content' => get_the_content()
]);
```

All with the same reusable snippet of HTML. Data is passed in from the 2nd argument and converted into PHP variables for you to use within the View. But here we're able to use any data source to provide content for the view.

Views are not a replacement to `get_template_part()`. You're free to use whichever solution is most appropriate for your use case, as both solve slightly different problems.

## Archive Selector

WordPress has the concept of "Post Type Archive" pages, which are not real pages, but they're used to act as the archive for a set of posts. Often you need to add custom text to these pages, and there is no "nice" way to do it.

The archive selector will try to handle this for you. Anytime you create a public post type with `has_archive` set to true, Origin will create a setting in the Theme Options that you can use to assign a real page to the archive.

You can get this page from the archive page by calling `origin_get_archive_page()` which will return a `WP_Post` for the attached page. You can then access the content for the archive like any other page.

```php
// archive-event.php
$page = origin_get_archive_page('event');

echo get_the_title($page->ID);
echo get_field('banner_image', $page->ID);
```

## Frontend/Assets

The theme uses SASS for styles, ES2016 for JavaScript, and Webpack for bundling it all together. 

### NPM Scripts

The following NPM scripts are available to use:

```
npm start       # Alias of `npm run watch`
npm run dev     # Alias of `npm run build`
npm run watch   # Runs webpack, and watches file in production mode
npm run build   # Runs webpack in production mode
npm run watch:dev   # Runs webpack, and watches files in dev mode
npm run build:dev   # Runs webpack in dev mode
```

### JSON Configuration

You can share data between PHP, JS & SASS using the `/assets/config.json` file. Anything stored within the JSON will be imported into SASS as variables using `sass-loader`. This can be useful when you need to share things like colours between your styles and your PHP/WordPress install.

You should not write to this file dynamically.

## theme-\*.php Files

There are 3 main files where the majority of functions & filters can be found.

### theme-setup.php

The `theme-setup.php` file should be used to register or setup data. Think of it is a bootstrapping file. You can see we use this file to register menus, widgets, post types etc.

### theme-filters.php

Anytime you need to hook into WordPress or a plugin using `add_action` or `add_filter`, it should be done within the `theme-filters.php` file.

### theme-functions.php

Any functions that you'd like to re-use in your code should go here. Not those used for actions/filters.

## Misc Functions

### origin_cache()

The `origin_cache()` function is a wrapper around the Transient API. You can use this to easily cache data from third party API's.
```php
// origin_cache($key, $time, $callback);

$tweets = origin_cache('tweets_indigotreesays', 15, function () {
    return get_tweets_from_twitter('indigotreesays');
});
```

### dd()

The `dd()` function is a general purpose die & dump function. It'll dump out anything you give it, and run `die()`.
```php
// dd(...$params)

dd($variable, 'some text', $_SERVER);
```

### array_get()

The `array_get()` function retrieves a value from a deeply nested array using "dot" notation, while also allowing for a default value.
```php
// array_get($array, $get, $default = null)

$array = ['data' => ['tweets' => ['name' => 'Indigo Tree']]];

array_get($array, 'data.tweets.name'); // Indigo Tree
```

<?php

namespace Origin\Theme;

class ShortcodeDocumentation
{
    public function __construct()
    {
        add_action('media_buttons', [$this, 'addMediaButtons'], 15);
        add_action('admin_footer', [$this, 'addModal']);
    }

    public function addMediaButtons()
    {
        printf(
            '<a href="%1$s" title="%2$s" id="#show-shortcodes" class="button thickbox">%2$s</a>',
            '#TB_inline?width=1000&height=1000&inlineId=origin-shortcodes',
            'Shortcodes'
        );
    }

    public function addModal()
    {
        $shortcodes = $this->getShortcodes();
        $origin_shortcodes = $this->filterOriginShortcodes($shortcodes);
        $other_shortcodes = $this->filterOtherShortcodes($shortcodes);

        ?><div id="origin-shortcodes" class="origin-shortcodes" style="display:none;">
            
            <?php if (!empty($shortcodes)) : ?>
                <div class="origin-shortcodes__documentation">
                    <h2>Shortcodes</h2>
                    <ul>
                        <?php foreach ($origin_shortcodes as $tag => $shortcode) : ?>
                            <li><a href="#origin-shortcode-<?= esc_attr($tag); ?>">[<?= $tag; ?>]</a></li>
                        <?php endforeach; ?>
                    </ul>
                    <hr />
                    <?php foreach ($origin_shortcodes as $tag => $shortcode) : ?>
                        <h2 id="origin-shortcode-<?= esc_attr($tag); ?>">[<?= $tag; ?>]</h2>
                        <?php if (!empty($shortcode['summary'])) : ?>
                            <p><?= $shortcode['summary']; ?></p>
                        <?php endif; ?>
                        <?php if (!empty($shortcode['description'])) : ?>
                            <p><?= $shortcode['description']; ?></p>
                        <?php endif; ?>
                        <?php if (!empty($shortcode['example'])) : ?>
                            <h4>Example</h4>
                            <pre><code><?= $shortcode['example']; ?></code></pre>
                        <?php endif; ?>
                        <?php if (!empty($shortcode['attrs'])) : ?>
                            <h4>Attributes</h4>
                            <table class="widefat striped">
                                <thead>
                                    <tr>
                                        <th width="50%">Attr</th>
                                        <th>Default</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($shortcode['attrs'] as $k => $v) : ?>
                                        <tr>
                                            <td><?= $k; ?></td>
                                            <td><?= $v !== '' ? $v : '--'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                        <hr />
                    <?php endforeach; ?>

                    <h2>Additional Shortcodes</h2>
                    <ul>
                        <?php foreach ($other_shortcodes as $tag => $shortcode) : ?>
                            <li><code>[<?= $tag; ?>]</code></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else : ?>
                <p>No shortcodes were found.</p>
            <?php endif; ?>

        </div>

        <style>

            #TB_window #TB_ajaxContent {
                height: calc(100% - 48px) !important;
                width: auto !important;
            }
            #TB_window #TB_ajaxContent hr {
                margin: 20px 0;
            }

        </style><?php
    }

    protected function getShortcodes()
    {
        global $shortcode_tags;

        $shortcodes = [];

        foreach ($shortcode_tags as $tag => $callback) {
            $shortcodes[$tag] = $this->normalizeShortcode($tag, $callback);
        }

        return $shortcodes;
    }

    protected function normalizeShortcode($tag, $shortcode)
    {
        $attrs = [];
        $origin = false;
        $description = $summary = $example = '';
        $self_closing = true;

        if (is_array($shortcode) && is_a($shortcode[0], 'Origin\Theme\Shortcodes\Shortcode')) {
            $class = $shortcode[0];
            $attrs = $class->fields();
            $origin = true;
            if ($dockblock = $this->getShortcodeDocblock($class)) {
                $description = (string) $dockblock->getDescription();
                $summary = (string) $dockblock->getSummary();
            }
            $example = $this->getExample($tag, $class);
            $self_closing = $this->hasClosingTag($class);
        }

        return compact('tag', 'attrs', 'origin', 'summary', 'description', 'example', 'self_closing');
    }

    protected function filterOriginShortcodes($shortcodes)
    {
        return array_filter($shortcodes, function ($s) { return $s['origin']; });
    }

    protected function filterOtherShortcodes($shortcodes)
    {
        return array_filter($shortcodes, function ($s) { return !$s['origin']; });
    }

    protected function getShortcodeDocblock($class)
    {
        $factory  = \phpDocumentor\Reflection\DocBlockFactory::createInstance();
        $docblock = (new \ReflectionClass($class))->getDocComment();

        if (empty($docblock)) {
            return null;
        }

        return $factory->create($docblock);
    }

    protected function getExample($tag, $class)
    {
        $show_close = $this->hasClosingTag($class);
        $attrs = array_filter($class->fields());

        $attrs = implode(' ', array_map(function ($key, $value) {
            return $key.'="'.$value.'"';
        }, array_keys($attrs), $attrs));

        $open = trim("{$tag} {$attrs}");
        return $show_close ? "[{$open}] ... [/$tag]" : "[{$open}]";
    }

    protected function hasClosingTag($class)
    {
        $method = new \ReflectionMethod($class, 'callback');

        $start_line = $method->getStartLine() - 1;
        $length = $method->getEndLine() - $start_line;
        $params = $method->getParameters();

        $content_var = isset($params[1]) ? $params[1]->getName() : null;

        if (is_null($content_var)) {
            return false;
        }

        $source = file($method->getFileName());
        $output = implode("", array_slice($source, $start_line, $length));
        
        if (preg_match_all('/\$'.$content_var.'*\b/', $output, $matches) > 1) {
            return true;
        }

        return false;
    }
}
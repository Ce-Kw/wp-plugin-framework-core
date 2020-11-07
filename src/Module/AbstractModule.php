<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\Shortcode\AbstractShortcode;
use WP_Widget;

abstract class AbstractModule implements ModuleInterface
{
    /**
     * @var PostType[]
     */
    private array $postTypes = [];

    /**
     * @var AbstractShortcode[]
     */
    private array $shortcodes = [];

    /**
     * @var WP_Widget[]
     */
    private array $widgets = [];

    public function addPostType(PostType $postType): void
    {
        $this->postTypes[] = $postType;
    }

    public function getPostTypes(): array
    {
        return $this->postTypes;
    }

    public function addShortcode(AbstractShortcode $shortcode): void
    {
        $this->shortcodes[] = $shortcode;
    }

    public function getShortcodes(): array
    {
        return $this->shortcodes;
    }

    public function addWidget(WP_Widget $widget): void
    {
        $this->widgets[] = $widget;
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }
}

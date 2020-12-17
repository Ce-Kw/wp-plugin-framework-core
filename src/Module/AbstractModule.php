<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\Event\Schedule;
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

    /**
     * @var ModuleInfoDTO[]
     */
    private array $modulesInfos;

    public function activate(Schedule $schedule) {} // phpcs:ignore

    public function deactivate(Schedule $schedule) {} // phpcs:ignore

    abstract public function init();

    public function addPostType(PostType $postType): AbstractModule
    {
        $this->postTypes[] = $postType;
        return $this;
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

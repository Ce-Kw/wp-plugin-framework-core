<?php

namespace CEKW\WpPluginFramework\Core\Module;

use Auryn\Injector;
use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\Shortcode\AbstractShortcode;
use WP_Widget;

abstract class AbstractModule implements ModuleInterface
{
    private ?Injector $injector = null;
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

    abstract public function init();

    public function addAction(string $tag, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        add_action($tag, function () use ($callback) {
            $args = [];
            foreach (func_get_args() as $key => $value) {
                $args[':' . $key] = $value;
            }

            $this->injector->execute($callback, $args);
        }, $priority, $acceptedArgs);
    }

    public function addFilter(string $tag, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        add_filter($tag, function () use ($callback) {
            $args = [];
            foreach (func_get_args() as $key => $value) {
                $args[':' . $key] = $value;
            }

            $this->injector->execute($callback, $args);
        }, $priority, $acceptedArgs);
    }

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

    public function setInjector(Injector $injector): void
    {
        $this->injector = $injector;
    }
}
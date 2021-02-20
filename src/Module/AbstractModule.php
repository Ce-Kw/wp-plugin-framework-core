<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use Exception;
use CEKW\WpPluginFramework\Core\Event\EventInterface;
use CEKW\WpPluginFramework\Core\Hook\HookSubscriberInterface;
use CEKW\WpPluginFramework\Core\Shortcode\AbstractShortcode;
use WP_Widget;

abstract class AbstractModule implements ModuleInterface
{
    private array $actions = [];
    private array $filters = [];

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
        $this->actions[] = compact('tag', 'callback', 'priority', 'acceptedArgs');
    }

    public function addEvent(EventInterface $event): void
    {
        $this->actions[] = [
            'tag' => $event->getTag(),
            'callback' => $event,
            'priority' => 10,
            'acceptedArgs' => 1,
        ];
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function addFilter(string $tag, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->filters[] = compact('tag', 'callback', 'priority', 'acceptedArgs');
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function addHookSubscriber(HookSubscriberInterface $hookSubscriber)
    {
        foreach ($hookSubscriber->getSubscribedHooks() as $tag => $options) {
            $callback = [$hookSubscriber, $options[0]];
            if (!is_callable($callback)) {
                throw new Exception("{$hookSubscriber} should implement {$options[0]} method.");
            }

            $this->addAction($tag, $callback, $options[1] ?? 10, $options[2] ?? 1);
        }
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

    public function addSettings(array $settings): void
    {
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
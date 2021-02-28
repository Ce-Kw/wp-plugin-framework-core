<?php

namespace CEKW\WpPluginFramework\Core\Module;

use Auryn\Injector;
use CEKW\WpPluginFramework\Core\ContentType\PostType;
use Exception;
use CEKW\WpPluginFramework\Core\Event\EventInterface;
use CEKW\WpPluginFramework\Core\Hook\HookSubscriberInterface;
use CEKW\WpPluginFramework\Core\Shortcode\AbstractShortcode;
use WP_Widget;

abstract class AbstractModule implements ModuleInterface
{
    use ModuleUtilsTrait;

    private array $actions = [];
    private array $filters = [];
    private Injector $injector;

    /**
     * @var PostType[]
     */
    private array $postTypes = [];

    /**
     * @var AbstractShortcode[]
     */
    private array $shortcodes = [];
    private string $templateDirPath;

    /**
     * @var WP_Widget[]
     */
    private array $widgets = [];

    final public function __construct(string $rootDirPath, Injector $injector)
    {
        $this->templateDirPath = $rootDirPath . 'templates/';
        $this->injector = $injector;
    }

    abstract public function init();

    public function addAction(string $tag, callable $callback, int $priority = 10, int $acceptedArgs = 1): AbstractModule
    {
        $this->actions[] = compact('tag', 'callback', 'priority', 'acceptedArgs');

        return $this;
    }

    public function addEvent(EventInterface $event): AbstractModule
    {
        $this->addAction($event->getTag(), $event, 10, 1);

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function addFilter(string $tag, callable $callback, int $priority = 10, int $acceptedArgs = 1): AbstractModule
    {
        $this->filters[] = compact('tag', 'callback', 'priority', 'acceptedArgs');

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function addHookSubscriber(HookSubscriberInterface $hookSubscriber): AbstractModule
    {
        foreach ($hookSubscriber->getSubscribedHooks() as $tag => $options) {
            $callback = [$hookSubscriber, $options[0]];
            if (!is_callable($callback)) {
                throw new Exception("{$hookSubscriber} should implement {$options[0]} method.");
            }

            $this->addAction($tag, $callback, $options[1] ?? 10, $options[2] ?? 1);
        }

        return $this;
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

    public function addShortcode(AbstractShortcode $shortcode): AbstractModule
    {
        $this->shortcodes[] = $shortcode;

        return $this;
    }

    public function getShortcodes(): array
    {
        return $this->shortcodes;
    }

    public function addWidget(WP_Widget $widget): AbstractModule
    {
        $this->widgets[] = $widget;

        return $this;
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }
}
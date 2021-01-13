<?php

namespace CEKW\WpPluginFramework\Core;

use Auryn\Injector;

class HookCollector
{
    /**
     * @var ModuleInterface[] $modules
     */
    private array $modules = [];
    private ?Injector $injector = null;

    /**
     * @param ModuleInterface[] $modules
     */
    public function __construct(array $modules, Injector $injector)
    {
        $this->modules = $modules;
        $this->injector = $injector;
    }

    public function activation(): void
    {
        foreach ($this->modules as $module) {
            if (!method_exists($module, 'activate')) {
                continue;
            }

            $this->injector->execute([$module, 'activate']);
        }
    }

    public function addHooks()
    {
        foreach ($this->modules as $module) {
            foreach ($module->getActions() as $action) {
                add_action($action['tag'], function () use ($action) {
                    $args = [];
                    foreach (func_get_args() as $key => $value) {
                        $args[':' . $key] = $value;
                    }

                    $this->injector->execute($action['callback'], $args);
                }, $action['priority'], $action['acceptedArgs']);
            }

            foreach ($module->getFilters() as $filter) {
                add_filter($filter['tag'], function () use ($filter) {
                    $args = [];
                    foreach (func_get_args() as $key => $value) {
                        $args[':' . $key] = $value;
                    }

                    return $this->injector->execute($filter['callback'], $args);
                }, $filter['priority'], $filter['acceptedArgs']);
            }
        }
    }

    public function cmb2AdminInit(): void
    {
        $this->createMetaBoxes();
    }

    public function deactivation(): void
    {
        foreach ($this->modules as $module) {
            if (!method_exists($module, 'deactivate')) {
                continue;
            }

            $this->injector->execute([$module, 'deactivate']);
        }
    }

    public function init(): void
    {
        $this->addShortcodes();
        $this->registerContentTypes();
    }

    public function widgetsInit(): void
    {
        $this->registerWidgets();
    }

    private function addShortcodes(): void
    {
        foreach ($this->modules as $module) {
            foreach ($module->getShortcodes() as $shortcode) {
                add_shortcode($shortcode->getTag(), [$shortcode, 'render']);
            }
        }
    }

    private function createMetaBoxes(): void
    {
        foreach ($this->modules as $module) {
            foreach ($module->getPostTypes() as $postType) {
                foreach ($postType->getMetaBoxes() as $metaBox) {
                    new_cmb2_box($metaBox->getArgs());

                    foreach ($postType->getTaxonomies() as $taxonomy) {
                        foreach ($taxonomy->getMetaBoxes() as $metaBox) {
                            new_cmb2_box($metaBox);
                        }
                    }
                }
            }
        }
    }

    private function registerContentTypes(): void
    {
        foreach ($this->modules as $module) {
            foreach ($module->getPostTypes() as $postType) {
                register_post_type($postType->getKey(), $postType->getArgs());

                foreach ($postType->getTaxonomies() as $taxanomy) {
                    register_taxonomy($taxanomy->getKey(), $postType->getKey(), $taxanomy->getArgs());
                }
            }
        }
    }

    private function registerWidgets(): void
    {
        foreach ($this->modules as $module) {
            foreach ($module->getWidgets() as $widget) {
                register_widget($widget);
            }
        }
    }
}
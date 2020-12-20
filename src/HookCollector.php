<?php

namespace CEKW\WpPluginFramework\Core;

use CEKW\WpPluginFramework\Core\Event\Schedule;

class HookCollector
{
    /**
     * @var ModuleInterface[] $modules
     */
    private array $modules = [];
    private ?Schedule $schedule = null;

    /**
     * @param ModuleInterface[] $modules
     */
    public function __construct(array $modules, Schedule $schedule)
    {
        $this->modules = $modules;
        $this->schedule = $schedule;
    }

    public function activation(): void
    {
        foreach ($this->modules as $module) {
            if (!method_exists($module, 'activate')) {
                continue;
            }

            $module->activate($this->schedule);
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

            $module->deactivate($this->schedule);
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
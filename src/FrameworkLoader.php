<?php

namespace CEKW\WpPluginFramework\Core;

use CEKW\WpPluginFramework\Core\Module\ModuleInterface;
use CEKW\WpPluginFramework\Core\Package\PackageInterface;

class FrameworkLoader
{
    private string $rootDirPath;
    private string $rootDirUrl;
    private string $subDirSources = 'src';

    /**
     * @var ModuleInterface[] $modules
     */
    private array $modules = [];

    public function getRootDirPath():string {
        return $this->rootDirPath.(substr($this->rootDirPath,-1)!=='/'?'/':'');
    }
    public function setRootDirPath(string $rootDirPath): FrameworkLoader
    {
        $this->rootDirPath = $rootDirPath;

        return $this;
    }

    public function setRootDirUrl(string $rootDirUrl): FrameworkLoader
    {
        $this->rootDirUrl = $rootDirUrl;

        return $this;
    }

    public function loadPackage(PackageInterface $package): FrameworkLoader
    {
        $package->setDirPath($this->getRootDirPath());
        $package->setDirUrl($this->rootDirUrl);
        $package->load();

        return $this;
    }

    public function loadModules(string $namespace): FrameworkLoader
    {
        $modulesDirectory = $this->getRootDirPath() . $this->getSubDirSources();
        foreach (scandir($modulesDirectory) as $subDirectory) {
            if (in_array($subDirectory, ['.', '..'])) {
                continue;
            }

            $classname = $namespace . '\\' . $subDirectory . '\\' . $subDirectory;
            if (!class_exists($classname)) {
                continue;
            }

            $instance = new $classname();
            if (!$instance instanceof ModuleInterface) {
                continue;
            }

            add_action('init', [$instance, 'init']);

            if (method_exists($instance, 'admin')) {
                add_action('admin_init', [$instance, 'admin']);
            }

            $this->modules[] = $instance;
        }

        return $this;
    }

    public function init(): FrameworkLoader
    {
        add_action('init', [$this, 'registerPostTypesTaxonomies']);
        add_action('cmb2_admin_init', [$this, 'createMetaBoxes']);
        add_action('widgets_init', [$this, 'registerWidgets']);

        $this->addShortcodes();

        return $this;
    }

    public function activate()
    {
        foreach ($this->modules as $module) {
            if (!method_exists($module, 'activate')) {
                continue;
            }

            $module->activate();
        }
    }

    public function deactivate()
    {
        foreach ($this->modules as $module) {
            if (!method_exists($module, 'deactivate')) {
                continue;
            }

            $module->deactivate();
        }
    }

    public function registerPostTypesTaxonomies()
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

    public function createMetaBoxes()
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

    public function registerWidgets()
    {
        foreach ($this->modules as $module) {
            foreach ($module->getWidgets() as $widget) {
                register_widget($widget);
            }
        }
    }

    private function addShortcodes()
    {
        foreach ($this->modules as $module) {
            foreach ($module->getShortcodes() as $shortcode) {
                add_shortcode($shortcode->getTag(), [$shortcode, 'render']);
            }
        }
    }

    public function getSubDirSources(): string
    {
        return $this->subDirSources;
    }

    public function setSubDirSources(string $subDirSources): FrameworkLoader
    {
        $this->subDirSources = $subDirSources;
        return $this;
    }
}
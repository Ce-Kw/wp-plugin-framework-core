<?php

namespace CEKW\WpPluginFramework\Core;

use CEKW\WpPluginFramework\Core\Module\ModuleInterface;
use CEKW\WpPluginFramework\Core\Package\PackageInterface;

use const WP_CLI;

final class FrameworkLoader
{
    private string $dirPath;
    private string $dirUrl;

    /**
     * @var ModuleInterface[] $modules
     */
    private array $modules = [];

    public function setDirPath(string $dirPath): FrameworkLoader
    {
        $this->dirPath = $dirPath;

        return $this;
    }

    public function setDirUrl(string $dirUrl): FrameworkLoader
    {
        $this->dirUrl = $dirUrl;

        return $this;
    }

    public function loadPackage(PackageInterface $package): FrameworkLoader
    {
        $package->setDirPath($this->dirPath);
        $package->setDirUrl($this->dirUrl);
        $package->load();

        return $this;
    }

    public function loadModules(string $namespace): FrameworkLoader
    {
        $modulesDirectory = $this->dirPath . 'src';
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

            $instance->init();

            if (method_exists($instance, 'admin')) {
                add_action('admin_init', [$instance, 'admin']);
            }

            $this->modules[] = $instance;
        }

        return $this;
    }

    public function init()
    {
        add_action('init', [$this, 'registerPostTypesTaxonomies']);
        add_action('cmb2_admin_init', [$this, 'createMetaBoxes']);
        add_action('widgets_init', [$this, 'registerWidgets']);

        $this->addShortcodes();

        $cliCommands = $this->dirPath . 'config/routes/command.php';
        if (defined('WP_CLI') && WP_CLI && file_exists($cliCommands)) {
            include_once $cliCommands;
        }
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
                    new_cmb2_box($metaBox);

                    foreach ($postType->getTaxonomies() as $taxanomy) {
                        foreach ($taxanomy->getMetaBoxes() as $metaBox) {
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
}
<?php

namespace CEKW\WpPluginFramework\Core;

use CEKW\WpPluginFramework\Core\Module\ModuleInterface;
use CEKW\WpPluginFramework\Core\Package\PackageInterface;

final class Loader
{
    private string $basename = '';
    private string $rootDirPath = '';
    private string $rootDirUrl = '';

    /**
     * @var ModuleInterface[] $modules
     */
    private array $modules = [];

    public function __construct(string $file)
    {
        $this->basename = plugin_basename($file);
        $this->rootDirPath = plugin_dir_path($file);
        $this->rootDirUrl = plugin_dir_url($file);
    }

    public function loadPackage(PackageInterface $package): Loader
    {
        $package->setDirPath($this->rootDirPath);
        $package->setDirUrl($this->rootDirUrl);
        $package->load();

        return $this;
    }

    public function loadModules(string $srcDir, string $namespace): Loader
    {
        $modulesDirectory = $this->rootDirPath . trailingslashit($srcDir);
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

    public function init(): void
    {
        add_action('cmb2_admin_init', [$this, 'createMetaBoxes']);
        add_action('init', [$this, 'registerContentTypes']);
        add_action('widgets_init', [$this, 'registerWidgets']);

        $this->addShortcodes();
    }

    public function activate(): void
    {
        foreach ($this->modules as $module) {
            if (!method_exists($module, 'activate')) {
                continue;
            }

            $module->activate();
        }
    }

    public function deactivate(): void
    {
        foreach ($this->modules as $module) {
            if (!method_exists($module, 'deactivate')) {
                continue;
            }

            $module->deactivate();
        }
    }

    public function registerContentTypes(): void
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

    public function createMetaBoxes(): void
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

    public function registerWidgets(): void
    {
        foreach ($this->modules as $module) {
            foreach ($module->getWidgets() as $widget) {
                register_widget($widget);
            }
        }
    }

    private function addShortcodes(): void
    {
        foreach ($this->modules as $module) {
            foreach ($module->getShortcodes() as $shortcode) {
                add_shortcode($shortcode->getTag(), [$shortcode, 'render']);
            }
        }
    }
}
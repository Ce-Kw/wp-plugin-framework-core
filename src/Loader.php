<?php

namespace CEKW\WpPluginFramework\Core;

use Auryn\Injector;
use CEKW\WpPluginFramework\Core\Module\ModuleInterface;
use CEKW\WpPluginFramework\Core\Package\PackageInterface;

use const WP_CLI;

final class Loader
{
    private string $basename = '';
    private string $file = '';
    private ?Injector $injector = null;
    private string $prefix = '';

    /**
     * @var ModuleInterface[] $modules
     */
    private array $modules = [];

    /**
     * @var ModuleInfoDTO[]
     */
    private array $moduleInfos = [];
    private string $rootDirPath = '';
    private string $rootDirUrl = '';

    public function __construct(string $file)
    {
        $this->basename = plugin_basename($file);
        $this->file = $file;
        $this->injector = new Injector();
        $this->prefix = preg_replace('/-app$/', '', dirname($this->basename));
        $this->rootDirPath = plugin_dir_path($file);
        $this->rootDirUrl = plugin_dir_url($file);
    }

    public function loadPackage(PackageInterface $package): Loader
    {
        $package
            ->setDirPath($this->rootDirPath)
            ->setDirUrl($this->rootDirUrl)
            ->setInjector($this->injector)
            ->load();

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

            $instance->setInjector($this->injector);
            $instance->init();

            if (is_admin() && method_exists($instance, 'admin')) {
                $instance->admin();
            }

            $this->modules[] = $instance;

            $infoDto = new ModuleInfoDTO();
            $infoDto->name = $subDirectory;
            $infoDto->rootDir = $modulesDirectory . $subDirectory;
            $infoDto->postTypes = $instance->getPostTypes();

            $this->moduleInfos[] = $infoDto;
        }

        return $this;
    }

    public function init(): void
    {
        $this->injector->share($GLOBALS['wpdb']);

        $hookCollector = new HookCollector($this->modules, $this->injector);

        register_activation_hook($this->file, [$hookCollector, 'activation']);
        register_deactivation_hook($this->file, [$hookCollector, 'deactivation']);

        add_action('cmb2_admin_init', [$hookCollector, 'cmb2AdminInit']);
        add_action('init', [$hookCollector, 'init']);
        add_action('widgets_init', [$hookCollector, 'widgetsInit']);

        $this->addModuleInfoPage();
        $this->loadCliCommands();
        $this->loadLanguageFiles();
    }

    private function addModuleInfoPage(): void
    {
        if (!is_admin()) {
            return;
        }

        $moduleInfoPage = new ModuleInfoPage(ModuleInfoListTable::class, $this->moduleInfos);

        add_action('admin_menu', [$moduleInfoPage, 'addPage']);
        add_filter('plugin_action_links_' . $this->basename, [$moduleInfoPage, 'addLink']);
    }

    private function loadCliCommands(): void
    {
        $cliCommands = $this->rootDirPath . 'config/routes/command.php';
        if (defined('WP_CLI') && WP_CLI && file_exists($cliCommands)) {
            include_once $cliCommands;
        }
    }

    private function loadLanguageFiles(): void
    {
        if (!is_dir($this->rootDirPath . 'lang/')) {
            return;
        }

        load_plugin_textdomain($this->prefix, false, dirname($this->basename) . '/lang/');
    }
}
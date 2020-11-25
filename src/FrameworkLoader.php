<?php

namespace CEKW\WpPluginFramework\Core;

use CEKW\WpPluginFramework\Core\DTO\AssetDefinitionDTO;
use CEKW\WpPluginFramework\Core\DTO\ModuleInfoDTO;
use CEKW\WpPluginFramework\Core\lib\WpAction;
use CEKW\WpPluginFramework\Core\Module\ModuleInterface;
use CEKW\WpPluginFramework\Core\Package\PackageInterface;
use CEKW\WpPluginFramework\Core\View\AdminPageView;
use CEKW\WpPluginFramework\Core\View\ModulesAdminTableView;
use WP_List_Table;

class FrameworkLoader
{
    private string $rootDirPath;
    private string $rootDirUrl;
    private string $subDirSources = 'src';

    /**
     * @var ModuleInterface[] $modules
     */
    private array $modules = [];
    /**
     * @var ModuleInfoDTO[]
     */
    private array $moduleInfos = [];

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
            $infoDto = new ModuleInfoDTO();
            $infoDto->name = $subDirectory;
            $infoDto->rootDir = $modulesDirectory . (substr($modulesDirectory,-1)!=='/'?'/':'') . $subDirectory;

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
            $instance->setApplicationName($subDirectory);

            add_action(WpAction::INIT, [$instance, 'init']);

            if (method_exists($instance, 'admin')) {
                $infoDto->useAdminInit = true;
                add_action(WpAction::ADMIN_INIT, [$instance, 'admin']);
            }

            $this->modules[] = $instance;

            $infoDto->adminScripts = array_map(function($script)use($subDirectory){
                $dto = $script->getObjectCopy();
                $dto->source = $this->rootDirUrl.$this->getSubDirSources().'/'.$subDirectory.'/'.$script->source;
                return $dto;
            }, $instance->getScripts('admin'));
            $infoDto->adminStyles = array_map(function($style)use($subDirectory){
                $dto = $style->getObjectCopy();
                $dto->source = $this->rootDirUrl.$this->getSubDirSources().'/'.$subDirectory.'/'.$style->source;
                return $dto;
            }, $instance->getStyles('admin'));
            $infoDto->scripts = array_map(function($script)use($subDirectory){
                $dto = $script->getObjectCopy();
                $dto->source = $this->rootDirUrl.$this->getSubDirSources().'/'.$subDirectory.'/'.$script->source;
                return $dto;
            }, $instance->getScripts());
            $infoDto->styles = array_map(function($style)use($subDirectory){
                $dto = $style->getObjectCopy();
                $dto->source = $this->rootDirUrl.$this->getSubDirSources().'/'.$subDirectory.'/'.$style->source;
                return $dto;
            }, $instance->getStyles());

            $this->moduleInfos[] = $infoDto;
        }

        return $this;
    }

    public function init(): FrameworkLoader
    {
        add_action(WpAction::ADMIN_MENU,function (){
            add_menu_page('CEKW','CEKW','manage_options','cekw',function(){
                $page = new AdminPageView();
                $overviewTable = new ModulesAdminTableView();
                $overviewTable
                    ->set_modules_infos($this->moduleInfos)
                    ->prepare_items()
                ;

                $page
                    ->setActiveSideTabId(isset($_GET['sideTab']) ? $_GET['sideTab'] : 0)
                    ->addTitleLink('Dokumentation','https://github.com/Ce-Kw/wp-plugin-framework-core/blob/master/Readme.md')
                    ->addSideTab('Moduleinfos',$overviewTable)
                ;
                echo $page;
            });
        });
        add_action(WpAction::ADMIN_ENQUEUE_SCRIPTS,function(){
            /**
             * @var ModuleInterface $module
             */
            array_map(function($module){
                /**
                 * @var AssetDefinitionDTO  $script
                 */
                foreach($module->getScripts('admin') as $script){
                    $version = ($script->version === true ?
                        date( 'YmdHi', filemtime( $this->rootDirPath.$this->getSubDirSources().'/'.$module->getApplicationName().'/'.$script->source) ) :
                        $script->version);
                    wp_enqueue_script(
                        $script->name,
                        $this->rootDirUrl.$this->getSubDirSources().'/'.$module->getApplicationName().'/'.$script->source,
                        $script->dependencies,
                        $version,
                        $script->inFooter
                    );
                }
                /**
                 * @var AssetDefinitionDTO  $script
                 */
                foreach($module->getStyles('admin') as $style){
                    $version = ($style->version === true ?
                        date( 'YmdHi', filemtime( $this->rootDirPath.$this->getSubDirSources().'/'.$module->getApplicationName().'/'.$style->source) ) :
                        $style->version);
                    wp_enqueue_style(
                        $style->name,
                        $this->rootDirUrl.$this->getSubDirSources().'/'.$module->getApplicationName().'/'.$style->source,
                        $style->dependencies,
                        $version,
                        $style->inFooter
                    );
                }
            },$this->modules);
        });
        add_action(WpAction::INIT,function(){
            add_action(WpAction::WP_ENNQUEUE_SCRIPTS,function(){
                /**
                 * @var ModuleInterface $module
                 */
                array_map(function($module){
                    /**
                     * @var AssetDefinitionDTO  $script
                     */
                    foreach($module->getScripts() as $script){
                        $version = ($script->version === true ?
                            date( 'YmdHi', filemtime( $this->rootDirPath.$this->getSubDirSources().'/'.$module->getApplicationName().'/'.$script->source) ) :
                            $script->version);
                        wp_enqueue_script(
                            $script->name,
                            $this->rootDirUrl.$this->getSubDirSources().'/'.$module->getApplicationName().'/'.$script->source,
                            $script->dependencies,
                            $version,
                            $script->inFooter
                        );
                    }
                    /**
                     * @var AssetDefinitionDTO  $script
                     */
                    foreach($module->getStyles() as $style){
                        $version = ($style->version === true ?
                            date( 'YmdHi', filemtime( $this->rootDirPath.$this->getSubDirSources().'/'.$module->getApplicationName().'/'.$style->source) ) :
                            $style->version);
                        wp_enqueue_style(
                            $style->name,
                            $this->rootDirUrl.$this->getSubDirSources().'/'.$module->getApplicationName().'/'.$style->source,
                            $style->dependencies,
                            $version,
                            $style->inFooter
                        );
                    }
                },$this->modules);
            });
            $this->registerContentTypes();
        });
        add_action(WpAction::CMB2_ADMIN_MENU, [$this, 'createMetaBoxes']);
        add_action(WpAction::WIDGETS_INIT, [$this, 'registerWidgets']);

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

    public function registerContentTypes()
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
                }
                foreach ($postType->getTaxonomies() as $taxonomy) {
                    foreach ($taxonomy->getMetaBoxes() as $metaBox) {
                        new_cmb2_box($metaBox);
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
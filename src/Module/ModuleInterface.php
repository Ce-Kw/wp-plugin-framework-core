<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\DTO\AssetDefinitionDTO;
use CEKW\WpPluginFramework\Core\DTO\ModuleInfoDTO;
use CEKW\WpPluginFramework\Core\ShortcodeInterface;
use CEKW\WpPluginFramework\Core\ContentType\Taxonomy;
use WP_Widget;

interface ModuleInterface
{
    public function activate():ModuleInterface;
    public function deactivate():ModuleInterface;
    public function init():ModuleInterface;

    /**
     * @return PostType[]
     */
    public function getPostTypes(): array;

    /**
     * @return ShortcodeInterface[]
     */
    public function getShortcodes(): array;

     /**
     * @return WP_Widget[]
     */
    public function getWidgets(): array;

    /**
     * @param string $enviroment
     * @return AssetDefinitionDTO[]
     */
    public function getScripts(string $enviroment='normal'):array;

    /**
     * @param string $enviroment
     * @return AssetDefinitionDTO[]
     */
    public function getStyles(string $enviroment='normal'):array;

    public function getApplicationName():string;
    public function setApplicationName(string $name):ModuleInterface;
}
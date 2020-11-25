<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\DTO\AssetDefinitionDTO;
use CEKW\WpPluginFramework\Core\DTO\ModuleInfoDTO;
use CEKW\WpPluginFramework\Core\Shortcode\AbstractShortcode;
use WP_Widget;

abstract class AbstractModule implements ModuleInterface
{
    private string $applicationName;
    private array $assets = [
        'scripts'=>['admin'=>[],'normal'=>[]],
        'styles'=>['admin'=>[],'normal'=>[]]
    ];

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

    /**
     * @var ModuleInfoDTO[]
     */
    private array $modulesInfos;

    public function activate(): ModuleInterface{ return $this; }

    public function deactivate(): ModuleInterface{ return $this; }

    public function init(): ModuleInterface{ return $this; }

    public function addPostType(PostType $postType): AbstractModule
    {
        $this->postTypes[] = $postType;
        return $this;
    }

    public function getPostTypes(): array
    {
        return $this->postTypes;
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

    public function addScript(AssetDefinitionDTO $assetDefinitionDTO, string $enviroment='normal'):ModuleInterface {
        $this->assets['scripts'][$enviroment][] = $assetDefinitionDTO;
        return $this;
    }

    public function addStyle(AssetDefinitionDTO $assetDefinitionDTO, string $enviroment='normal'):ModuleInterface {
        $this->assets['styles'][$enviroment][] = $assetDefinitionDTO;
        return $this;
    }

    public function getScripts(string $enviroment = 'normal'): array
    {
        return $this->assets['scripts'][$enviroment];
    }

    public function getStyles(string $enviroment = 'normal'): array
    {
        return $this->assets['styles'][$enviroment];
    }

    final public function getApplicationName():string {
        return $this->applicationName;
    }
    final public function setApplicationName(string $name):ModuleInterface {
        $this->applicationName = $name;
        return $this;
    }
}

<?php

namespace CEKW\WpPluginFramework\Core\DTO;

class ModuleInfoDTO
{
    public string $name;
    public string $rootDir;
    public bool $useAdminInit = false;

    /**
     * @var AssetDefinitionDTO[]
     */
    public array $scripts = [];
    /**
     * @var AssetDefinitionDTO[]
     */
    public array $styles = [];
    /**
     * @var AssetDefinitionDTO[]
     */
    public array $adminScripts = [];
    /**
     * @var AssetDefinitionDTO[]
     */
    public array $adminStyles = [];

    public array $postTypes;
}
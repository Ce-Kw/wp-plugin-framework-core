<?php


namespace CEKW\WpPluginFramework\Core\DTO;


class AssetDefinitionDTO
{
    public array $dependencies;
    public bool $inFooter;
    public string $name;
    public string $source;
    public string $version;

    private function __construct(){ }

    public static function create(string $name, string $source, array $dependencies = [],$version=false, bool $inFooter=false):AssetDefinitionDTO {
        $dto = new AssetDefinitionDTO();
        $dto->name = $name;
        $dto->source = $source;
        $dto->dependencies = $dependencies;
        $dto->version = $version;
        $dto->inFooter = $inFooter;

        return $dto;
    }
    public function getObjectCopy():AssetDefinitionDTO {
        return self::create($this->name, $this->source, $this->dependencies, $this->version, $this->inFooter);
    }
}
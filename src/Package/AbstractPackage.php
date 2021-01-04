<?php

namespace CEKW\WpPluginFramework\Core\Package;

use Auryn\Injector;
use Exception;

abstract class AbstractPackage implements PackageInterface
{
    protected string $dirPath;
    protected string $dirUrl;
    protected ?Injector $injector = null;

    public function setDirPath(string $dirPath): PackageInterface
    {
        $this->dirPath = $dirPath;

        return $this;
    }

    public function setDirUrl(string $dirUrl): PackageInterface
    {
        $this->dirUrl = $dirUrl;

        return $this;
    }

    public function setInjector(Injector $injector): PackageInterface
    {
        $this->injector = $injector;

        return $this;
    }

    protected function loadConfig(string $config)
    {
        $configFolder = $this->dirPath . 'config';
        $configPath = "{$configFolder}/{$config}";
        if (!file_exists($configPath)) {
            throw new Exception("Configuration file {$config} could not be found in {$configFolder}.");
        }

        include_once $configPath;
    }
}
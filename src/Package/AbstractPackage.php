<?php

namespace CEKW\WpPluginFramework\Core\Package;

use Exception;

abstract class AbstractPackage implements PackageInterface
{
    protected string $dirPath;
    protected string $dirUrl;

    public function setDirPath(string $dirPath): void
    {
        $this->dirPath = $dirPath;
    }

    public function setDirUrl(string $dirUrl): void
    {
        $this->dirPath = $dirUrl;
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
<?php

namespace CEKW\WpPluginFramework\Core\Package;

use Auryn\Injector;

interface PackageInterface
{
    public function setDirPath(string $dirPath): PackageInterface;
    public function setDirUrl(string $dirUrl): PackageInterface;
    public function setInjector(Injector $injector): PackageInterface;
    public function load(): void;
}
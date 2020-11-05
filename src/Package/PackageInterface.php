<?php

namespace CEKW\WpPluginFramework\Core\Package;

interface PackageInterface
{
    public function setDirPath(string $dirPath): void;
    public function setDirUrl(string $dirUrl): void;
    public function load(): void;
}
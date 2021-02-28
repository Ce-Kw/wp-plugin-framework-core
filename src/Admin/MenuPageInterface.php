<?php

namespace CEKW\WpPluginFramework\Core\Admin;

interface MenuPageInterface
{
    public function setTemplateDirPath(string $templateDirPath): void;
    public function getArgs(): array;
    public function render(): void;
}
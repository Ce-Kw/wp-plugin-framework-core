<?php

namespace CEKW\WpPluginFramework\Core\Admin;

abstract class AbstractSubmenuPage extends AbstractMenuPage
{
    protected string $parentSlug;

    public function getArgs(): array
    {
        return [
            $this->parentSlug,
            $this->pageTitle,
            $this->menuTitle ?? $this->pageTitle,
            $this->capability,
            $this->resolveKeyFromClassName('SubmenuPage'),
            [$this, 'render'],
            $this->position
        ];
    }
}
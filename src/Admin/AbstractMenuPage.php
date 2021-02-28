<?php

namespace CEKW\WpPluginFramework\Core\Admin;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;
use CEKW\WpPluginFramework\Core\RenderTemplateTrait;

abstract class AbstractMenuPage implements MenuPageInterface
{
    use DynamicKeyResolverTrait;
    use RenderTemplateTrait;

    protected string $pageTitle;
    protected string $menuTitle;
    protected string $capability;
    protected string $iconUrl;
    protected int $position;
    protected string $templateDirPath;

    public function setTemplateDirPath(string $templateDirPath): void
    {
        $this->templateDirPath = $templateDirPath;
    }

    public function getArgs(): array
    {
        return [
            $this->pageTitle,
            $this->menuTitle ?? $this->pageTitle,
            $this->capability,
            $this->resolveKeyFromClassName('MenuPage'),
            [$this, 'render'],
            $this->iconUrl ?? '',
            $this->position ?? null
        ];
    }

    protected function json(array $data): string
    {
        return json_encode($data);
        exit;
    }

    protected function redirect(string $url): void
    {
        wp_safe_redirect($url, 302, static::class);
        exit;
    }

    protected function redirectToReferer(): void
    {
        $this->redirect(wp_get_referer());
    }
}
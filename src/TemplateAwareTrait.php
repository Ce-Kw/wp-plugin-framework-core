<?php

namespace CEKW\WpPluginFramework\Core;

trait TemplateAwareTrait
{
    public function renderTemplate(string $templateFile, array $data = []): string
    {
        if (!file_exists($templateFile)) {
            return '';
        }

        ob_start();
        extract($data, EXTR_SKIP);
        unset($data);
        include $templateFile;

        return ob_get_clean();
    }
}
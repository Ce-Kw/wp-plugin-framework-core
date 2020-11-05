<?php

namespace CEKW\WpPluginFramework\Core\Shortcode;

interface ShortcodeInterface
{
    public function getTag(): string;
    public function render(): string;
}
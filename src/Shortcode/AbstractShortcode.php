<?php

namespace CEKW\WpPluginFramework\Core\Shortcode;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

abstract class AbstractShortcode implements ShortcodeInterface
{
    use DynamicKeyResolverTrait;

    public function getTag(): string
    {
        return $this->resolveKeyFromClassName('Shortcode');
    }
}
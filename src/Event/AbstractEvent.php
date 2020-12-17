<?php

namespace CEKW\WpPluginFramework\Core\Event;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

abstract class AbstractEvent implements EventInterface
{
    use DynamicKeyResolverTrait;

    public function getHook(): string
    {
        return $this->resolveKeyFromClassName('Event', '_');
    }
}
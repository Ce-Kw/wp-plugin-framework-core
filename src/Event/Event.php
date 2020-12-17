<?php

namespace CEKW\WpPluginFramework\Core\Event;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

class Event
{
    use DynamicKeyResolverTrait;

    public function getHook(): string
    {
        return $this->resolveKeyFromClassName('Event', '_');
    }
}
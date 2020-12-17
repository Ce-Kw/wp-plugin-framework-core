<?php

namespace CEKW\WpPluginFramework\Core\Event;

interface EventInterface
{
    public function getHook(): string;
    public function __invoke();
}
<?php

namespace CEKW\WpPluginFramework\Core\Hook;

interface HookSubscriberInterface
{
    public function getSubscribedHooks(): array;
}
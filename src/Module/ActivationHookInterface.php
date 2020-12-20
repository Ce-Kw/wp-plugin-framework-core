<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\Event\Schedule;

interface ActivationHookInterface
{
    public function activate(Schedule $schedule);
}
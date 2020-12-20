<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\Event\Schedule;

interface DeactivationHookInterface
{
    public function deactivate(Schedule $schedule);
}
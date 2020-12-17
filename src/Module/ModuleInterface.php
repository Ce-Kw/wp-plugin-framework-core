<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\ShortcodeInterface;
use CEKW\WpPluginFramework\Core\Event\Schedule;
use WP_Widget;

interface ModuleInterface
{
    public function activate(Schedule $schedule);
    public function deactivate(Schedule $schedule);
    public function init();

    /**
     * @return PostType[]
     */
    public function getPostTypes(): array;

    /**
     * @return ShortcodeInterface[]
     */
    public function getShortcodes(): array;

     /**
     * @return WP_Widget[]
     */
    public function getWidgets(): array;
}
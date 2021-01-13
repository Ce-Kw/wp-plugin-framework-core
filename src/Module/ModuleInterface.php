<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\ShortcodeInterface;
use WP_Widget;

interface ModuleInterface
{
    public function getActions(): array;
    public function getFilters(): array;

    /**
     * @return PostType[]
     */
    public function getPostTypes(): array;

    /**
     * @return ShortcodeInterface[]
     */
    public function getShortcodes(): array;

    public function init();

     /**
     * @return WP_Widget[]
     */
    public function getWidgets(): array;
}
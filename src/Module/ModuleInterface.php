<?php

namespace CEKW\WpPluginFramework\Core\Module;

use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\ShortcodeInterface;
use CEKW\WpPluginFramework\Core\ContentType\Taxonomy;
use WP_Widget;

interface ModuleInterface
{
    public function activate():ModuleInterface;
    public function deactivate():ModuleInterface;
    public function init():ModuleInterface;

    /**
     * @return PostType[]
     */
    public function getPostTypes(): array;

    /**
     * @return Taxonomy[]
     */
    public function getTaxonomies(): array;

    /**
     * @return ShortcodeInterface[]
     */
    public function getShortcodes(): array;

     /**
     * @return WP_Widget[]
     */
    public function getWidgets(): array;
}
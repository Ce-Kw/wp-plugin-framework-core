<?php

namespace CEKW\WpPluginFramework\Core\Module;

use Auryn\Injector;
use CEKW\WpPluginFramework\Core\ContentType\PostType;
use CEKW\WpPluginFramework\Core\ShortcodeInterface;
use WP_Widget;

interface ModuleInterface
{
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
    public function setInjector(Injector $injector): void;
}
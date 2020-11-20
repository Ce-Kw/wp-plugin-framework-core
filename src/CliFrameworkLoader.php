<?php

namespace CEKW\WpPluginFramework\Core;

use CEKW\WpPluginFramework\Core\Module\ModuleInterface;
use CEKW\WpPluginFramework\Core\Package\PackageInterface;

use Exception;
use const WP_CLI;

class CliFrameworkLoader extends FrameworkLoader
{
    private string $subPathCliCommands = 'config/routes/command.php';

    public function init(): FrameworkLoader
    {
        parent::init();

        $cliCommands = $this->getRootDirPath() . $this->subPathCliCommands;

        if(!file_exists($cliCommands)){
            throw new Exception($cliCommands.' does not exist.');
        }

        if (defined('WP_CLI') && WP_CLI) {
            include_once $cliCommands;
        }

        return $this;
    }

    public function getSubPathCliCommands(): string
    {
        return $this->subPathCliCommands;
    }

    public function setSubPathCliCommands(string $subPathCliCommands): CliFrameworkLoader
    {
        $this->subPathCliCommands = $subPathCliCommands;
        return $this;
    }

}
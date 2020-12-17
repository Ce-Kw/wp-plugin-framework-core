<?php

namespace CEKW\WpPluginFramework\Core;

class ModuleInfoPage
{
    private const PAGE_SLUG = 'cekw-modules';

    private string $moduleInfoListTableClass = '';

    /**
     * @var ModuleInfoDTO[] $modulInfos
     */
    private array $moduleInfos = [];

    public function __construct(string $moduleInfoListTableClass, array $moduleInfos)
    {
        $this->moduleInfoListTableClass = $moduleInfoListTableClass;
        $this->moduleInfos = $moduleInfos;
    }

    public function addLink($links): array
    {
        $links[] = sprintf('<a href="%s">Modules</a>', add_query_arg(['page' => self::PAGE_SLUG], admin_url('admin.php')));

        return $links;
    }

    public function addPage()
    {
        add_submenu_page('', 'CEKW', 'CEKW', 'manage_options', self::PAGE_SLUG, [$this, 'render']);
    }

    public function render()
    {
        $moduleInfoListTable = new $this->moduleInfoListTableClass();
        $moduleInfoListTable->setModulesInfos($this->moduleInfos);
        $moduleInfoListTable->prepare_items();

        ob_start();
        include dirname(__DIR__) . '/templates/module-info.php';

        echo ob_get_clean();
    }
}
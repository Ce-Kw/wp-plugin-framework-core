<?php

namespace CEKW\WpPluginFramework\Core;

use CEKW\WpPluginFramework\Core\ModuleInfoDTO;
use WP_List_Table;

class ModuleInfoListTable extends WP_List_Table
{
    use TemplateAwareTrait;

    // phpcs:ignore
    public function column_assets(ModuleInfoDTO $item): string
    {
        return
            $this->formatAssetInfo('Frontend-Scripts', $item->scripts) .
            $this->formatAssetInfo('Frontend-Styles', $item->styles) .
            $this->formatAssetInfo('Backend-Scripts', $item->adminScripts) .
            $this->formatAssetInfo('Backend-Styles', $item->adminStyles);
    }

    /**
     * @param ModuleInfoDTO $item
     * @param string $columnName
     */
    // phpcs:ignore
    public function column_default($item, $columnName): string
    {
        return $item->$columnName;
    }

    // phpcs:ignore
    public function column_posttypes(ModuleInfoDTO $item)
    {
        if (count($item->postTypes) === 0) {
            return '';
        }

        $result = '<ul style="margin:0">';

        foreach ($item->postTypes as $postType) {
            $result .= $this->renderTemplate(dirname(__DIR__) . '/templates/module-info-column.php', [
                'content' => ($postType->isPublic() ? '&#128083;' : '&#128374;') . $postType->getKey(),
                'alert' => str_replace('\\', '\\\\', get_class($postType))
            ]);
        }

        $result .= '</ul>';

        return $result;
    }

    // phpcs:ignore
    public function get_columns(): array
    {
        return [
            'name' => 'Name',
            'assets' => 'Assets',
            'posttypes' => 'PostTypes',
            'useAdminInit' => 'Admin-Init'
        ];
    }

    // phpcs:ignore
    public function get_sortable_columns(): array
    {
        return [
            'name' => ['name', true]
        ];
    }

    // phpcs:ignore
    public function prepare_items(): void
    {
        // phpcs:ignore
        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns()
        ];
    }

    /**
     * @param ModuleInfoDTO[] $modulInfos
     *
     * @return $this
     */
    public function setModulesInfos(array $modulInfos): ModuleInfoListTable
    {
        $this->items = $modulInfos;

        return $this;
    }

    /**
     * @param ModuleInfoDTO $item
     */
    // phpcs:ignore
    public function single_row($item): void
    {
        printf('<tr class="module module--%s">%s</tr>', $item->name, $this->single_row_columns($item));
    }

    /**
     * @param AssetDefinitionDTO[] $assetDTOs
     */
    private function formatAssetInfo(string $title, array $assetDTOs): string
    {
        if (count($assetDTOs) === 0) {
            return '';
        }

        $result = '<ul style="margin:0">' . $title;

        foreach ($assetDTOs as $dto) {
            $result .= $this->renderTemplate(dirname(__DIR__) . '/templates/module-info-column.php', [
                'content' => $dto->name,
                'alert' => $dto->source
            ]);
        }

        $result .= '</ul>';

        return $result;
    }
}
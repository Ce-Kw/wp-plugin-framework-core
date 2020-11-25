<?php

namespace CEKW\WpPluginFramework\Core\View;

use CEKW\WpPluginFramework\Core\DTO\AssetDefinitionDTO;
use CEKW\WpPluginFramework\Core\DTO\ModuleInfoDTO;
use WP_List_Table;

class ModulesAdminTableView extends WP_List_Table
{
    public function column_default($item, $columnName)
    {
        return $item->$columnName;
    }

    public function column_assets(ModuleInfoDTO $item)
    {
        return $this->format_asset_info('Frontend-Scripts',$item->scripts).
            $this->format_asset_info('Frontend-Styles',$item->styles).
            $this->format_asset_info('Backend-Scripts',$item->adminScripts).
            $this->format_asset_info('Backend-Styles',$item->adminStyles)
        ;
    }

    protected function display_tablenav( $which ) {
        return '';
    }

    /**
     * @param AssetDefinitionDTO[] $assetDTOs
     */
    private function format_asset_info(string $title, array $assetDTOs){
        if(count($assetDTOs)===0){return '';}
        $result='<ul style="margin: 0;">'.$title;
        array_map(function($dto)use(&$result){
            $result .= '<li style="margin-left: 20px;">
                            <span style="min-width: 40%;display: inline-block">'.$dto->name.'</span>
                            <span style="cursor:pointer;" onClick="alert(\''.$dto->source.'\')">&#10067</span>
                        </li>';
        },$assetDTOs);
        $result .='</ul>';

        return $result;
    }

    public function get_columns() {
        $columns = [
            'name' => 'Name',
            'assets' => 'Assets',
            'useAdminInit' => 'Admin-Init'
        ];

        return $columns;
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'name' => array( 'name', true ),
            'city' => array( 'city', false )
        );

        return $sortable_columns;
    }

    /**
     * @param ModuleInfoDTO $item
     */
    public function single_row($item) {
        echo '<tr class="module module--'.$item->name.'">';
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    public function __toString()
    {
       ob_start();
       $this->display();
       $result = ob_get_contents();
       ob_end_clean();

        return $result;
    }

    /**
     * @param ModuleInfoDTO[] $modulInfos
     * @return $this
     */
    public function set_modules_infos(array $modulInfos): ModulesAdminTableView {
        $this->items = $modulInfos;
        return $this;
    }

    public function prepare_items(): ModulesAdminTableView
    {
        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns()
        ];

        return $this;
    }
}
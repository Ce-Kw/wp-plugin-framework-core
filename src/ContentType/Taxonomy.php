<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

/**
 * @method array getLabelArgs
 * @method LabelInfo setLabelArgs
 * @method string getLabelName
 * @method LabelInfo setLabelName
 * @method string getLabelSingularName
 * @method LabelInfo setLabelSingularName
 * @method string getLabelAddNew
 * @method LabelInfo setLabelAddNew
 * @method string getLabelAddNewItem
 * @method LabelInfo setLabelAddNewItem
 * @method string getLabelEditItem
 * @method LabelInfo setLabelEditItem
 * @method string getLabelNewItem
 * @method LabelInfo setLabelNewItem
 * @method string getLabelViewItem
 * @method LabelInfo setLabelViewItem
 * @method string getLabelViewItems
 * @method LabelInfo setLabelViewItems
 * @method string getLabelMenuName
 * @method LabelInfo setLabelMenuName
 * @method string getLabelParentItemColon
 * @method LabelInfo setLabelParentItemColon
 * @method string getLabelAllItems
 * @method LabelInfo setLabelAllItems
 * @method string getLabelUpdateItem
 * @method LabelInfo setLabelUpdateItem
 * @method string getLabelSearchItems
 * @method LabelInfo setLabelSearchItems
 * @method string getLabelNotFound
 * @method LabelInfo setLabelNotFound
 * @method string getLabelNotFoundInTrash
 * @method LabelInfo setLabelNotFoundInTrash
 */
abstract class Taxonomy extends ContentType
{
    use DynamicKeyResolverTrait;

    public function getKey(): string
    {
        return $this->resolveKeyFromClassName('Taxonomy');
    }

    public function getArgs(): array
    {
        $args = array(
            'public'                => $this->getIsPublic(),
            'hierarchical'          => $this->isHierarchical,
            'publicly_queryable'    => is_null($this->isPubliclyQueryable) ? $this->getIsPublic() : $this->isPubliclyQueryable,
            'show_ui'               => is_null($this->showUi) ? $this->getIsPublic() : $this->showUi,
            'show_in_menu'          => is_null($this->showInMenu) ? $this->showUi : $this->showInMenu,
            'show_in_nav_menus'     => is_null($this->showInNavMenus) ? $this->getIsPublic() : $this->showInNavMenus,
            'show_in_rest'          => $this->showInRest,
            'rest_base'             => empty($this->restBase) ? $this->getKey() : $this->restBase,
            'rest_controller_class' => empty($this->restControllerClass) ? WP_REST_Terms_Controller::class : $this->restControllerClass,
            'labels'                => $this->getLabelArgs(),
        );

        return $args;
    }
}
<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\AbstractExtenderBridge;
use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;
use WP_REST_Posts_Controller;

/**
 * @method array getLabelArgs
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
 * @method MetaBox[] getMetaBoxes
 */
abstract class PostType extends ContentType
{

    private ?bool $excludeFromSearch    = null;
    private ?bool $showInAdminBar       = null;
    private ?int $menuPosition          = null;
    private string $menuIcon            = '';
    private string $capabilityType      = 'post';
    private array $capabilities         = array();
    private bool $mapMetaCap            = false;
    private array $supports = array( 'title', 'editor' );
    private bool $hasArchive            = false;
    private array $rewrite              = array();

    /**
     * @var Taxonomy[]
     */
    private array $taxonomies = array();

    public function getKey(): string
    {
        return $this->resolveKeyFromClassName('PostType');
    }

    public function addTaxonomy(Taxonomy $taxonomy): void
    {
        $this->taxonomies[] = $taxonomy;
    }

    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }

    public function addMeta(string $key, string $type = 'string', array $args = [])
    {
        $args = array_merge([
            'show_in_rest' => true,
            'single' => true,
            'type' => $type,
        ], $args);

        register_post_meta($this->getKey(), $key, $args);
    }

    public function getArgs(): array
    {
        $args = array(
            'public'                => $this->getIsPublic(),
            'hierarchical'          => $this->isHierarchical,
            'exclude_from_search'   => is_null($this->excludeFromSearch) ? ! $this->getIsPublic() : $this->excludeFromSearch,
            'publicly_queryable'    => is_null($this->isPubliclyQueryable) ? $this->getIsPublic() : $this->isPubliclyQueryable,
            'show_ui'               => is_null($this->showUi) ? $this->getIsPublic() : $this->showUi,
            'show_in_menu'          => is_null($this->showInMenu) ? $this->showUi : $this->showInMenu,
            'show_in_nav_menus'     => is_null($this->showInNavMenus) ? $this->getIsPublic() : $this->showInNavMenus,
            'show_in_admin_bar'     => is_null($this->showInAdminBar) ? $this->showInMenu : $this->showInAdminBar,
            'show_in_rest'          => $this->showInRest,
            'rest_base'             => empty($this->restBase) ? $this->getKey() : $this->restBase,
            'rest_controller_class' => empty($this->restControllerClass) ? WP_REST_Posts_Controller::class : $this->restControllerClass,
            'menu_position'         => $this->menuPosition,
            'menu_icon'             => $this->menuIcon,
            'supports'              => $this->getSupports(),
            'has_archive'           => $this->hasArchive,
            'labels'                => $this->getLabelArgs(),
        );

        if (! empty($this->capabilityType)) {
            $args['capability_type'] = $this->capabilityType;
        }

        if (! empty($this->mapMetaCap)) {
            $args['map_meta_cap'] = $this->mapMetaCap;
        }


        if (! empty($this->capabilities)) {
            $args['capabilities'] = $this->capabilities;
        }

        if (! empty($this->rewrite)) {
            $args['rewrite'] = $this->rewrite;
        }

        return $args;
    }

    public function setExcludeFromSearch(bool $excludeFromSearch): PostType
    {
        $this->excludeFromSearch = $excludeFromSearch;

        return $this;
    }

    public function setShowInAdminBar(bool $showInAdminBar): PostType
    {
        $this->showInAdminBar = $showInAdminBar;

        return $this;
    }

    public function setMenuPosition(int $menuPosition): PostType
    {
        $this->menuPosition = $menuPosition;

        return $this;
    }

    public function setMenuIcon(string $menuIcon): PostType
    {
        $this->menuIcon = $menuIcon;

        return $this;
    }

    /**
     * @param string|array $capabilityType
     */
    public function setCapabilityType($capabilityType): PostType
    {
        $this->capabilityType = $capabilityType;

        return $this;
    }

    public function setCapabilities(array $capabilities): PostType
    {
        $this->capabilities = $capabilities;

        return $this;
    }

    public function setMapMetaCap(bool $mapMetaCap): PostType
    {
        $this->mapMetaCap = $mapMetaCap;

        return $this;
    }

    public function setHasArchive(bool $hasArchive): PostType
    {
        $this->hasArchive = $hasArchive;

        return $this;
    }

    public function setRewrite(array $rewrite): PostType
    {
        $this->rewrite = $rewrite;

        return $this;
    }

    public function addSupports(string $support): ContentType
    {
        $this->supports[] = $support;
        return $this;
    }
    public function getSupports(): array
    {
        return $this->supports;
    }

    public function removeSupports(string $support): ContentType
    {
        if (($key = array_search($support, $this->supports)) !== false) {
            unset($this->supports[$key]);
        }

        return $this;
    }
}

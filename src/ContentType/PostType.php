<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\AbstractExtenderBridge;
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
class PostType extends AbstractExtenderBridge
{
    use DynamicKeyResolverTrait;
    use MetaBoxTrait;

    private bool $isPublic = false;

    /**
     * @var Taxonomy[]
     */
    private array $taxonomies = [];

    public function __construct()
    {
        $this->addExtend(new LabelInfo());
    }

    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): PostType
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getKey(): string
    {
        return $this->resolveKeyFromClassName('PostType');
    }

    public function getArgs(): array
    {
        return [
            'public' => $this->isPublic,
            'supports' => ['title'],
            'labels' => $this->getLabelArgs()
        ];
    }

    public function addTaxonomy(Taxonomy $taxonomy): void
    {
        $this->taxonomies[] = $taxonomy;
    }

    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }
}
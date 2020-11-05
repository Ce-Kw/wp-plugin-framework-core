<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

class PostType
{
    use DynamicKeyResolverTrait;
    use LabelTrait;
    use MetaBoxTrait;

    private bool $isPublic = false;

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
            'labels' => [
                'name' => $this->labelName,
                'singular_name' => $this->labelSingularName,
                'menu_name' => $this->labelMenuName,
                'parent_item_colon' => $this->labelParentItemColon,
                'all_items' => $this->labelAllItems,
                'view_item' => $this->labelViewItem,
                'add_new_item' => $this->labelAddNewItem,
                'add_new' => $this->labelAddNew,
                'edit_item' => $this->labelEditItem,
                'update_item' => $this->labelUpdateItem,
                'search_items' => $this->labelSearchItems,
                'not_found' => $this->labelNotFound,
                'not_found_in_trash' => $this->labelNotFoundInTrash
            ]
        ];
    }
}
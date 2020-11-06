<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

class LabelInfo
{
    private string $labelName = '';
    private string $labelSingularName = '';
    private string $labelAddNew = '';
    private string $labelAddNewItem = '';
    private string $labelEditItem = '';
    private string $labelNewItem = '';
    private string $labelViewItem = '';
    private string $labelViewItems = '';
    private string $labelMenuName = '';
    private string $labelParentItemColon = '';
    private string $labelAllItems = '';
    private string $labelUpdateItem = '';
    private string $labelSearchItems = '';
    private string $labelNotFound = '';
    private string $labelNotFoundInTrash = '';

    public function getLabelName(): string
    {
        return $this->labelName;
    }
    public function setLabelName(string $labelName): LabelInfo
    {
        $this->labelName = $labelName;

        return $this;
    }

    public function getLabelSingularName(): string
    {
        return $this->labelSingularName;
    }
    public function setLabelSingularName(string $labelSingularName): LabelInfo
    {
        $this->labelSingularName = $labelSingularName;
        
        return $this;
    }

    public function getLabelMenuName(): string
    {
        return $this->labelMenuName;
    }
    public function setLabelMenuName(string $labelMenuName): LabelInfo
    {
        $this->labelMenuName = $labelMenuName;
        return $this;
    }

    public function getLabelParentItemColon(): string
    {
        return $this->labelParentItemColon;
    }
    public function setLabelParentItemColon(string $labelParentItemColon): LabelInfo
    {
        $this->labelParentItemColon = $labelParentItemColon;
        return $this;
    }

    public function getLabelAllItems(): string
    {
        return $this->labelAllItems;
    }
    public function setLabelAllItems(string $labelAllItems): LabelInfo
    {
        $this->labelAllItems = $labelAllItems;
        return $this;
    }

    public function getLabelViewItem(): string
    {
        return $this->labelViewItem;
    }
    public function setLabelViewItem(string $labelViewItem): LabelInfo
    {
        $this->labelViewItem = $labelViewItem;
        return $this;
    }

    public function getLabelAddNewItem(): string
    {
        return $this->labelAddNewItem;
    }
    public function setLabelAddNewItem(string $labelAddNewItem): LabelInfo
    {
        $this->labelAddNewItem = $labelAddNewItem;
        return $this;
    }

    public function getLabelAddNew(): string
    {
        return $this->labelAddNew;
    }
    public function setLabelAddNew(string $labelAddNew): LabelInfo
    {
        $this->labelAddNew = $labelAddNew;
        return $this;
    }

    public function getLabelEditItem(): string
    {
        return $this->labelEditItem;
    }
    public function setLabelEditItem(string $labelEditItem): LabelInfo
    {
        $this->labelEditItem = $labelEditItem;
        return $this;
    }

    public function getLabelUpdateItem(): string
    {
        return $this->labelUpdateItem;
    }
    public function setLabelUpdateItem(string $labelUpdateItem): LabelInfo
    {
        $this->labelUpdateItem = $labelUpdateItem;
        return $this;
    }

    public function getLabelSearchItems(): string
    {
        return $this->labelSearchItems;
    }
    public function setLabelSearchItems(string $labelSearchItems): LabelInfo
    {
        $this->labelSearchItems = $labelSearchItems;
        return $this;
    }

    public function getLabelNotFound(): string
    {
        return $this->labelNotFound;
    }
    public function setLabelNotFound(string $labelNotFound): LabelInfo
    {
        $this->labelNotFound = $labelNotFound;
        return $this;
    }

    public function getLabelNotFoundInTrash(): string
    {
        return $this->labelNotFoundInTrash;
    }
    public function setLabelNotFoundInTrash(string $labelNotFoundInTrash): LabelInfo
    {
        $this->labelNotFoundInTrash = $labelNotFoundInTrash;
        return $this;
    }

    public function getLabelNewItem(): string
    {
        return $this->labelNewItem;
    }
    public function setLabelNewItem(string $labelNewItem): LabelInfo
    {
        $this->labelNewItem = $labelNewItem;
        return $this;
    }

    public function getLabelViewItems(): string
    {
        return $this->labelViewItems;
    }
    public function setLabelViewItems(string $labelViewItems): LabelInfo
    {
        $this->labelViewItems = $labelViewItems;
        return $this;
    }

    public function getLabelArgs():array {
        return [
            'name' => $this->getLabelName(),
            'singular_name' => $this->getLabelSingularName(),
            'menu_name' => $this->getLabelMenuName(),
            'parent_item_colon' => $this->getLabelParentItemColon(),
            'all_items' => $this->getLabelAllItems(),
            'view_item' => $this->getLabelViewItem(),
            'add_new_item' => $this->getLabelAddNewItem(),
            'add_new' => $this->getLabelAddNew(),
            'edit_item' => $this->getLabelEditItem(),
            'update_item' => $this->getLabelUpdateItem(),
            'search_items' => $this->getLabelSearchItems(),
            'not_found' => $this->getLabelNotFound(),
            'not_found_in_trash' => $this->getLabelNotFoundInTrash()
        ];
    }
}
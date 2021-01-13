<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\AbstractExtenderBridge;
use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

abstract class ContentType extends AbstractExtenderBridge
{
    use DynamicKeyResolverTrait;

    protected bool $isHierarchical        = false;
    protected bool $isPublic  = false;
    protected ?bool $isPubliclyQueryable  = null;
    protected ?bool $showUi               = null;
    protected ?bool $showInMenu           = null;
    protected ?bool $showInNavMenus       = null;
    protected bool $showInRest            = false;
    protected string $restBase            = '';
    protected string $restControllerClass = '';

    abstract public function init();

    final public function __construct()
    {
        $this->addExtend(new LabelInfo());
        $this->addExtend(new MetaBoxProvider());
        $this->init();
    }

    public function setIsPublic(bool $isPublic): ContentType
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsHierarchical(bool $isHierarchical): ContentType
    {
        $this->isHierarchical = $isHierarchical;

        return $this;
    }

    public function setIsPubliclyQueryable(bool $isPubliclyQueryable): ContentType
    {
        $this->isPubliclyQueryable = $isPubliclyQueryable;

        return $this;
    }

    public function setShowUi(bool $showUi): ContentType
    {
        $this->showUi = $showUi;

        return $this;
    }

    public function setShowInMenu(bool $showInMenu): ContentType
    {
        $this->showInMenu = $showInMenu;

        return $this;
    }

    public function setShowInNavMenus(bool $showInNavMenus): ContentType
    {
        $this->showInNavMenus = $showInNavMenus;

        return $this;
    }

    public function setShowInRest(bool $showInRest): ContentType
    {
        $this->showInRest = $showInRest;

        return $this;
    }

    public function setRestBase(string $restBase): ContentType
    {
        $this->restBase = $restBase;

        return $this;
    }

    public function setRestControllerClass(string $restControllerClass): ContentType
    {
        $this->restControllerClass = $restControllerClass;

        return $this;
    }

    public function addMetaBox(MetaBox $metaBox): ContentType
    {
        $metaBox->setObjectTypes([$this->getKey()]);
        $this->_addMetaBox($metaBox);
        return $this;
    }
}
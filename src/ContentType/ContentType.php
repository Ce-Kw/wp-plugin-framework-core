<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\AbstractExtenderBridge;
use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

abstract class ContentType extends AbstractExtenderBridge
{
    use DynamicKeyResolverTrait;

    private bool $isHierarchical        = false;
    private bool $isPublic  = false;
    private ?bool $isPubliclyQueryable  = null;
    private ?bool $showUi               = null;
	private ?bool $showInMenu           = null;
    private ?bool $showInNavMenus       = null;
    private bool $showInRest            = false;
    private string $restBase            = '';
    private string $restControllerClass = '';

    abstract function init();

    final public function __construct() {
        $this->addExtend( new LabelInfo() );
        $this->addExtend( new MetaBoxProvider() );
        $this->init();
    }

    public function setIsPublic( bool $isPublic ): ContentType {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getIsPublic():bool {
        return $this->isPublic;
    }

    public function setIsHierarchical( bool $isHierarchical ): ContentType {
		$this->isHierarchical = $isHierarchical;

		return $this;
	}

    public function setIsPubliclyQueryable( bool $isPubliclyQueryable ): ContentType {
		$this->isPubliclyQueryable = $isPubliclyQueryable;

		return $this;
    }
    
    public function setShowUi( bool $showUi ): ContentType {
		$this->showUi = $showUi;

		return $this;
	}

	public function setShowInMenu( bool $showInMenu ): ContentType {
		$this->showInMenu = $showInMenu;

		return $this;
	}

	public function setShowInNavMenus( bool $showInNavMenus ): ContentType {
		$this->showInNavMenus = $showInNavMenus;

		return $this;
    }
    
    public function setShowInRest( bool $showInRest ): ContentType {
		$this->showInRest = $showInRest;

		return $this;
	}

	public function setRestBase( string $restBase ): ContentType {
		$this->restBase = $restBase;

		return $this;
    }
    
    public function setRestControllerClass( string $restControllerClass ): ContentType {
		$this->restControllerClass = $restControllerClass;

		return $this;
	}

    public function addMetaBox(MetaBox $metaBox):ContentType {
        $metaBox->setObjectTypes([$this->getKey()]);
        $this->_addMetaBox($metaBox);
        return $this;
    }
}
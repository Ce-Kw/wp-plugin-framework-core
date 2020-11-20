<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\AbstractExtenderBridge;
use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

abstract class ContentType extends AbstractExtenderBridge
{
    use DynamicKeyResolverTrait;

    private bool $isPublic  = false;
    private array $supports = array( 'title' );

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

    public function addSupports(string $support):ContentType {
        $this->supports[] = $support;
        return $this;
    }
    public function getSupports():array {
        return $this->supports;
    }

    public function addMetaBox(MetaBox $metaBox):ContentType {
        $metaBox->setObjectTypes([$this->getKey()]);
        $this->_addMetaBox($metaBox);
        return $this;
    }
}
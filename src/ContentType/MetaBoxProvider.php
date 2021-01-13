<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

class MetaBoxProvider
{
    private array $metaBoxes = [];

    public function _addMetaBox(MetaBox $metaBox): MetaBoxProvider
    {
        $this->metaBoxes[] = $metaBox;

        return $this;
    }

    public function getMetaBoxes(): array
    {
        return $this->metaBoxes;
    }
}

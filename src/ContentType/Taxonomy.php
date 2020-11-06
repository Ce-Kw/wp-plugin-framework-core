<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

class Taxonomy
{
    use DynamicKeyResolverTrait;
    use MetaBoxTrait;

    private LabelInfo $labelInfo;

    private bool $isPublic = false;

    public function getIsPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): Taxonomy
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    public function getKey(): string
    {
        return $this->resolveKeyFromClassName('Taxonomy');
    }

    public function getArgs(): array
    {
        return [
            'public' => $this->isPublic,
            'supports' => ['title'],
            'labels' => $this->labelInfo->getArgs()
        ];
    }

    public function getLabelInfo(): LabelInfo
    {
        return $this->labelInfo;
    }

    public function setLabelInfo(LabelInfo $labelInfo): Taxonomy
    {
        $this->labelInfo = $labelInfo;
        return $this;
    }
}
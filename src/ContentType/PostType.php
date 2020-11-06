<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

use CEKW\WpPluginFramework\Core\DynamicKeyResolverTrait;

class PostType
{
    use DynamicKeyResolverTrait;
    use MetaBoxTrait;

    private LabelInfo $labelInfo;

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
            'labels' => $this->labelInfo->getArgs()
        ];
    }

    public function getLabelInfo(): LabelInfo
    {
        return $this->labelInfo;
    }

    public function setLabelInfo(LabelInfo $labelInfo): PostType
    {
        $this->labelInfo = $labelInfo;
        return $this;
    }

}
<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

trait MetaBoxTrait
{
    private string $currentId;
    private array $metaBoxes = [];

    public function addMetaBox(string $title, string $context = 'normal', string $priority = 'deafult', bool $showNames = true): MetaBoxTrait
    {
        $this->currentId = md5($title);
        $this->metaBoxes[$this->currentId] = [
            'id' => $this->currentId,
            'title' => $title,
            'object_types' => [$this->getKey()],
            'context' => $context,
            'priority' => $priority,
            'show_names' => $showNames
        ];

        return $this;
    }

    public function addFields(array $fields): void
    {
        $this->metaBoxes[$this->currentId]['fields'] = $fields;
    }

    public function getMetaBoxes(): array
    {
        return $this->metaBoxes;
    }
}
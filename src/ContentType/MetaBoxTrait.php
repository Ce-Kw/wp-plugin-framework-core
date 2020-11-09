<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

trait MetaBoxTrait
{
    private string $currentId;
    private array $metaBoxes = [];

    public function addMetaBox(string $title, string $context = 'normal', string $priority = 'default', bool $showNames = true)
    {
        $this->currentId = md5($title);
        $this->metaBoxes[$this->currentId] = [
            'id' => $this->currentId,
            'title' => $title,
            'object_types' => [$this->getKey()],
            'context' => $this->showInRest && $context === 'normal' ? 'side' : $context,
            'priority' => $priority,
            'show_names' => $showNames
        ];

        return $this;
    }

    public function showOn(string $key, $value)
    {
        $this->metaBoxes[$this->currentId]['show_on'] = compact('key', 'value');

        return $this;
    }

    public function addFields(array $fields)
    {
        $this->metaBoxes[$this->currentId]['fields'] = $fields;

        return $this;
    }

    public function getMetaBoxes(): array
    {
        return $this->metaBoxes;
    }
}
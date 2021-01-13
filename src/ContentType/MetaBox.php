<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

class MetaBox
{
    private string $context;
    private array $objectTypes = [];
    private string $priority;
    private bool $showInRest = false;
    private bool $showNames = true;
    private string $title = '';
    private array $showOn = [];
    private array $fields = [];

    private function __construct()
    {
    }

    public static function create(string $title, string $context = 'normal', string $priority = 'default', bool $showNames = true): MetaBox
    {
        $metabox = new MetaBox();

        return $metabox
            ->setTitle($title)
            ->setContext($context)
            ->setPriority($priority)
            ->setShowNames($showNames)
        ;
    }

    public function getArgs(): array
    {
        $args = [
            'id' => md5(__NAMESPACE__ . $this->getTitle()),
            'fields' => $this->fields,
            'title' => $this->getTitle(),
            'objectTypes' => $this->objectTypes,
            'context' => $this->getShowInRest() && $this->getContext() === 'normal' ? 'side' : $this->getContext(),
            'priority' => $this->getPriority(),
            'showNames' => $this->getShowNames()
        ];

        if (count($this->showOn) > 0) {
            $args['showOn'] = compact('key', 'value');
        }

        return $args;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function setContext(string $context): MetaBox
    {
        $this->context = $context;

        return $this;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): MetaBox
    {
        $this->priority = $priority;

        return $this;
    }

    public function getShowInRest(): bool
    {
        return $this->showInRest;
    }

    public function setShowInRest(bool $showInRest): MetaBox
    {
        $this->showInRest = $showInRest;

        return $this;
    }

    public function getShowNames(): bool
    {
        return $this->showNames;
    }

    public function setShowNames(bool $showNames): MetaBox
    {
        $this->showNames = $showNames;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): MetaBox
    {
        $this->title = $title;

        return $this;
    }

    public function getObjectTypes(): array
    {
        return $this->objectTypes;
    }

    public function setObjectTypes(array $objectTypes): MetaBox
    {
        $this->objectTypes = $objectTypes;

        return $this;
    }

    public function addField(array $field): MetaBox
    {
        $this->fields[] = $field;

        return $this;
    }

    public function setFields(array $fields): MetaBox
    {
        $this->fields = $fields;

        return $this;
    }
}
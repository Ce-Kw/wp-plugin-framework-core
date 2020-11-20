<?php

namespace CEKW\WpPluginFramework\Core\ContentType;

class MetaBox
{
    private string $context;
    private array $object_types = [];
    private string $priority;
    private bool $show_in_rest = false;
    private bool $show_names = true;
    private string $title = '';
    private array $show_on = [];
    private array $fields = [];

    private function __construct(){}

    static function create(string $title, string $context = 'normal', string $priority = 'default', bool $showNames = true):MetaBox {
        $metabox = new MetaBox();

        return $metabox
            ->setTitle($title)
            ->setContext($context)
            ->setPriority($priority)
            ->setShowNames($showNames)
        ;
    }

    public function getArgs():array{
        $args = [
            'id' => md5(__NAMESPACE__.$this->getTitle()),
            'fields' => $this->fields,
            'title' => $this->getTitle(),
            'object_types' => $this->object_types,
            'context' => $this->getShowInRest() && $this->getContext() === 'normal' ? 'side' : $this->getContext(),
            'priority' => $this->getPriority(),
            'show_names' => $this->getShowNames()
        ];
        if(count($this->show_on)>0){
            $args['show_on'] = compact('key', 'value');
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
        return $this->show_in_rest;
    }
    public function setShowInRest(bool $show_in_rest): MetaBox
    {
        $this->show_in_rest = $show_in_rest;
        return $this;
    }

    public function getShowNames(): bool
    {
        return $this->show_names;
    }
    public function setShowNames(bool $show_names): MetaBox
    {
        $this->show_names = $show_names;
        return $this;
    }

    public function getTitle():string {
        return $this->title;
    }
    public function setTitle(string $title):MetaBox {
        $this->title = $title;
        return $this;
    }

    public function getObjectTypes():array {
        return $this->object_types;
    }
    public function setObjectTypes(array $object_types):MetaBox {
        $this->object_types = $object_types;
        return $this;
    }

    public function addField(string $field_name):MetaBox {
        $this->fields[] = [

        ];
        return $this;
    }
    public function setFields(array $fields):MetaBox {
        $this->fields = $fields;
        return $this;
    }
}

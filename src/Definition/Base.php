<?php

namespace Echron\XmlMapper\Definition;

class Base
{
    private $name;

    private $children;
    private $parent;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->children = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addChild(Base $subDefinition)
    {
        $this->children[$subDefinition->getName()] = $subDefinition;

        $subDefinition->setParent($this);
    }

    public function hasChild(string $name): bool
    {
        return isset($this->children[$name]);
    }

    public function getChild(string $name): Base
    {
        return $this->children[$name];
    }

    public function setParent(Base $parent)
    {
        $this->parent = $parent;
    }

    public function getParent(): Base
    {
        return $this->parent;
    }

    public function hasParent(): bool
    {
        return !is_null($this->parent);
    }
}
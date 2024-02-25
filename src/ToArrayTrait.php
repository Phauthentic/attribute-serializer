<?php

declare(strict_types=1);

namespace Phauthentic\AttributeSerializer;

trait ToArrayTrait
{
    protected ?SerializerInterface $attributeSerializer = null;

    protected function getAttributeSerializer(): SerializerInterface
    {
        if ($this->attributeSerializer === null) {
            $this->attributeSerializer = new Serializer();
        }

        return $this->attributeSerializer;
    }

    public function toArray(): array
    {
        return $this->getAttributeSerializer()->serialize($this);
    }
}

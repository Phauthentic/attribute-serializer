<?php

declare(strict_types=1);

namespace Phauthentic\AttributeSerializer;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT | Attribute::TARGET_PROPERTY)]
class Serialize
{
    public function __construct(public ?string $fieldName = null)
    {
    }
}

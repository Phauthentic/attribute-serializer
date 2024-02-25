<?php

declare(strict_types=1);

namespace Phauthentic\AttributeSerializer;

/**
 * Serializer interface for extracting data from objects annotated with the #[Serialize] attribute.
 *
 * This class provides a method, serialize, to transform an object into an associative array
 * by extracting data from its properties and constants based on the #[Serialize] attribute.
 */
interface SerializerInterface
{
    /**
     * @param object $object
     * @return array<string, mixed>
     */
    public function serialize(object $object): array;
}

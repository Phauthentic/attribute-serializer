<?php

declare(strict_types=1);

namespace Phauthentic\AttributeSerializer;

use ReflectionClass;

/**
 * Serializer class for extracting data from objects annotated with the #[Serialize] attribute.
 *
 * This class provides a method, serialize, to transform an object into an associative array
 * by extracting data from its properties and constants based on the #[Serialize] attribute.
 */
class Serializer implements SerializerInterface
{
    protected const ATTRIBUTE_NAME = Serialize::class;

    /**
     * @param object $object
     * @return array<string, mixed>
     */
    public function serialize(object $object): array
    {
        return $this->extract($object);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, \ReflectionProperty|\ReflectionClassConstant> $items
     * @param callable $getValue
     * @return array<string, mixed>
     */
    protected function reflectAttributes(array $data, array $items, callable $getValue): array
    {
        foreach ($items as $item) {
            $attributes = $item->getAttributes(self::ATTRIBUTE_NAME);

            foreach ($attributes as $attribute) {
                $instance = $attribute->newInstance();
                $name = $instance->fieldName ?? $item->getName();
                $value = $getValue($item);

                if (is_object($value)) {
                    $value = $this->extract($value);
                }

                $this->setArrayValue($data, $name, $value);
            }
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     * @param ReflectionClass $reflectionClass
     * @param object $object
     * @return array<string, mixed>
     */
    protected function reflectProperties(array $data, ReflectionClass $reflectionClass, object $object): array
    {
        $reflectionProperties = $reflectionClass->getProperties();

        $getValue = function ($item) use ($object) {
            return $item->getValue($object);
        };

        return $this->reflectAttributes($data, $reflectionProperties, $getValue);
    }

    /**
     * @param array<string, mixed> $data
     * @param ReflectionClass $reflectionClass
     * @return array<string, mixed>
     */
    protected function reflectConstants(array $data, ReflectionClass $reflectionClass): array
    {
        $reflectionConstants = $reflectionClass->getReflectionConstants();

        $getValue = function ($item) {
            return $item->getValue();
        };

        return $this->reflectAttributes($data, $reflectionConstants, $getValue);
    }

    /**
     * @param object $object
     * @return array<string, mixed>
     */
    protected function extract(object $object): array
    {
        $data = [];
        $reflectionClass = new ReflectionClass($object);

        $data = $this->reflectProperties($data, $reflectionClass, $object);
        $data = $this->reflectConstants($data, $reflectionClass);

        return $data;
    }

    /**
     * @param array<string, mixed> $array
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function setArrayValue(array &$array, string $key, $value): void
    {
        $keys = explode('.', $key);
        $currentArray = &$array;

        foreach ($keys as $nestedKey) {
            if (!isset($currentArray[$nestedKey]) || !is_array($currentArray[$nestedKey])) {
                $currentArray[$nestedKey] = [];
            }

            $currentArray = &$currentArray[$nestedKey];
        }

        $currentArray = $value;
    }
}

<?php

declare(strict_types=1);

namespace Phauthentic\AttributeSerializer\Tests;

use PHPUnit\Framework\TestCase;
use Phauthentic\AttributeSerializer\Serialize;
use Phauthentic\AttributeSerializer\Serializer;

class SerializerTest extends TestCase
{
    public function testSerializeObject(): void
    {
        $serializer = new Serializer();

        $object = new class {
            #[Serialize(fieldName: 'foo')]
            public string $bar = 'baz';

            #[Serialize]
            public int $count = 42;

            #[Serialize(fieldName: 'nested.item')]
            public array $nested = ['2ndLevel' => 'value'];

            public const CONSTANT_VALUE = 'constant';
        };

        $expected = [
            'foo' => 'baz',
            'count' => 42,
            'nested' => [
                'item' => [
                    '2ndLevel' => 'value'
                ]
            ],
        ];

        $result = $serializer->serialize($object);

        $this->assertEquals($expected, $result);
    }

    public function testSerializeObjectWithConstants(): void
    {
        $serializer = new Serializer();

        $object = new class
        {
            public const CONSTANT_STRING = 'string';
            public const CONSTANT_INT = 42;
            public const CONSTANT_BOOL = true;
            public const CONSTANT_ARRAY = ['value'];

            #[Serialize(fieldName: 'constants.string')]
            public const CONSTANT_STRING_SERIALIZED = self::CONSTANT_STRING;

            #[Serialize(fieldName: 'constants.int')]
            public const CONSTANT_INT_SERIALIZED = self::CONSTANT_INT;

            #[Serialize(fieldName: 'constants.bool')]
            public const CONSTANT_BOOL_SERIALIZED = self::CONSTANT_BOOL;

            #[Serialize(fieldName: 'constants.array')]
            public const CONSTANT_ARRAY_SERIALIZED = self::CONSTANT_ARRAY;
        };

        $expected = [
            'constants' => [
                'string' => 'string',
                'int' => 42,
                'bool' => true,
                'array' => ['value'],
            ],
        ];

        $result = $serializer->serialize($object);

        $this->assertEquals($expected, $result);
    }
}

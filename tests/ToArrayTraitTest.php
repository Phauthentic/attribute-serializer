<?php

declare(strict_types=1);

namespace Phauthentic\AttributeSerializer\Tests;

use Phauthentic\AttributeSerializer\Serialize;
use Phauthentic\AttributeSerializer\ToArrayTrait;
use PHPUnit\Framework\TestCase;

class ToArrayTraitTest extends TestCase
{
    public function testToArrayTrait(): void
    {
        $anonymousClass = new class {
            use ToArrayTrait;

            #[Serialize('username')]
            private string $name = 'trait test';
        };

        $result = $anonymousClass->toArray();

        $this->assertSame(['username' => 'trait test'], $result);
    }
}

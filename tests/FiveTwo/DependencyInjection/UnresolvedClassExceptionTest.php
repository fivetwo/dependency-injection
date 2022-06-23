<?php
/*
 * Copyright (c) 2022 Matthew Suhocki. All rights reserved.
 * Copyright (c) 2022 Five Two Foundation, LLC. All rights reserved.
 *
 * This software is licensed under the terms of the MIT License <https://opensource.org/licenses/MIT>.
 * The above copyright notice and this notice shall be included in all copies or substantial portions of this software.
 */

declare(strict_types=1);

namespace FiveTwo\DependencyInjection;

use PHPUnit\Framework\TestCase;

/**
 * Test suite for {@see UnresolvedClassException}.
 */
class UnresolvedClassExceptionTest extends TestCase
{
    public function testGetMessage_HasClassName_ContainsClassName(): void
    {
        $exception = new UnresolvedClassException(FakeClassNoConstructor::class);

        self::assertStringContainsString(FakeClassNoConstructor::class, $exception->getMessage());
    }

    public function testGetClassName_HasClassName_ReturnsClassName(): void
    {
        $exception =  new UnresolvedClassException(FakeClassNoConstructor::class);

        self::assertSame(FakeClassNoConstructor::class, $exception->getClassName());
    }
}

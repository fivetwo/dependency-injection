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

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Test suite for {@see DependencyInjectionException}.
 */
class DependencyInjectionExceptionTest extends TestCase
{
    public function test__construct(): void
    {
        $exception = new DependencyInjectionException('Message1');
        self::assertSame('Message1', $exception->getMessage());
    }

    public function test__construct_Composition(): void
    {
        $originalException = new DependencyInjectionException('Message1');
        $exception = new DependencyInjectionException('Message2', $originalException);
        self::assertMatchesRegularExpression('/Message2.*Message1/s', $exception->getMessage());
        self::assertNull($exception->getPrevious());
        self::assertSame($originalException, $exception->getConsolidatedException());
    }

    public function test__construct_NoCompositionForUnrelatedException(): void
    {
        $exception = new DependencyInjectionException('Message2', $previous = new Exception('Message1'));
        self::assertSame('Message2', $exception->getMessage());
        self::assertSame($previous, $exception->getPrevious());
        self::assertNull($exception->getConsolidatedException());
    }
}

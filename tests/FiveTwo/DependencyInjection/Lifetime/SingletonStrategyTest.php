<?php
/*
 * Copyright (c) 2022 Matthew Suhocki. All rights reserved.
 * Copyright (c) 2022 Five Two Foundation, LLC. All rights reserved.
 *
 * This software is licensed under the terms of the MIT License <https://opensource.org/licenses/MIT>.
 * The above copyright notice and this notice shall be included in all copies or substantial portions of this software.
 */

declare(strict_types=1);

namespace FiveTwo\DependencyInjection\Lifetime;

use FiveTwo\DependencyInjection\FakeClassNoConstructor;
use PHPUnit\Framework\TestCase;

/**
 * Test suite for {@see SingletonStrategy}.
 */
class SingletonStrategyTest extends TestCase
{
    /**
     * @return SingletonStrategy<FakeClassNoConstructor>
     */
    protected function createStrategy(): SingletonStrategy
    {
        /**
         * @phpstan-ignore-next-line PHPStan does not support generics on inherited constructors without repeating the
         * constructor {@link https://github.com/phpstan/phpstan/issues/3537#issuecomment-710038367}
         */
        return new SingletonStrategy(FakeClassNoConstructor::class);
    }

    public function testGet(): void
    {
        self::assertInstanceOf(
            FakeClassNoConstructor::class,
            $this->createStrategy()->get(fn () => new FakeClassNoConstructor())
        );
    }

    public function testGet_FactoryCalledOnlyOnce(): void
    {
        $strategy = $this->createStrategy();

        self::assertSame(
            $strategy->get(fn () => new FakeClassNoConstructor()),
            $strategy->get(fn () => new FakeClassNoConstructor())
        );
    }
}

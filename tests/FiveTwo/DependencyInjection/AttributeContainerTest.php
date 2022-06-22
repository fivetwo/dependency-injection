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

/**
 * Test suite for {@see AttributeContainer}.
 */
class AttributeContainerTest extends DependencyInjectionTestCase
{
    public function testGet_WithDefaultInjectorDefaultFactory_ReturnsAutowiredInstance(): void
    {
        $container = new AttributeContainer(FakeAttribute::class);

        self::assertInstanceOf(
            FakeClassWithAttribute::class,
            $container->get(FakeClassWithAttribute::class)
        );
    }

    public function testGet_WithExplicitInjectorExplicitFactory_ReturnsInstanceFromFactory(): void
    {
        $container = new AttributeContainer(
            FakeAttribute::class,
            factory: fn (string $className, FakeAttribute $attr) => new FakeClassWithAttribute($attr->value)
        );

        $result = $container->get(FakeClassWithAttribute::class);
        self::assertInstanceOf(FakeClassWithAttribute::class, $result);
        self::assertSame('test', $result->value);
    }

    public function testGet_WhenClassDoesNotHaveAttribute_ThrowsUnresolvedClassException(): void
    {
        $container = new AttributeContainer(FakeAttribute::class);

        self::assertUnresolvedClassException(
            FakeClassUsingContexts::class,
            fn () => $container->get(FakeClassUsingContexts::class)
        );
    }

    public function testGet_WhenClassDoesNotExist_ThrowsUnresolvedClassException(): void
    {
        $container = new AttributeContainer(FakeAttribute::class);

        /**
         * @psalm-suppress ArgumentTypeCoercion,UndefinedClass
         */
        self::assertUnresolvedClassException(
        /** @phpstan-ignore-next-line */
            'NonExistentClass',
            /** @phpstan-ignore-next-line */
            fn () => $container->get('NonExistentClass')
        );
    }

    public function testHas_WhenClassHasAttribute_ReturnsTrue(): void
    {
        $container = new AttributeContainer(FakeAttribute::class);

        self::assertTrue($container->has(FakeClassWithAttribute::class));
    }

    public function testHas_WhenClassDoesNotHaveAttribute_ReturnsFalse(): void
    {
        $container = new AttributeContainer(FakeAttribute::class);

        self::assertFalse($container->has(FakeClassUsingContexts::class));
    }

    public function testHas_WhenClassDoesNotExist_ReturnsFalse(): void
    {
        $container = new AttributeContainer(FakeAttribute::class);

        /**
         * @psalm-suppress ArgumentTypeCoercion,UndefinedClass
         * @phpstan-ignore-next-line
         */
        self::assertFalse($container->has('NonExistentClass'));
    }
}
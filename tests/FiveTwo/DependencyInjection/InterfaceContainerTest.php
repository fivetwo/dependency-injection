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
 * Test suite for {@see InterfaceContainer}.
 */
class InterfaceContainerTest extends DependencyInjectionTestCase
{
    public function testGet_WithDefaultInjectorAndDefaultFactory_ReturnsInstance(): void
    {
        $container = new InterfaceContainer(FakeClassNoConstructor::class);

        self::assertInstanceOf(
            FakeClassExtendsNoConstructor::class,
            $container->get(FakeClassExtendsNoConstructor::class)
        );
    }

    public function testGet_WithExplicitInjectorAndExplicitFactory_UsesInjectorAndFactory(): void
    {
        $container = self::createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with(FakeClassNoConstructor::class)
            ->willReturn(new FakeClassNoConstructor());
        $container->method('has')
            ->willReturn(true);

        $implContainer = new InterfaceContainer(
            FakeInterfaceOne::class,
            new Injector($container),
            fn (string $className, FakeClassNoConstructor $obj) => new FakeClassWithConstructor($obj)
        );

        self::assertInstanceOf(
            FakeClassWithConstructor::class,
            $implContainer->get(FakeClassWithConstructor::class)
        );
    }

    public function testGet_WithImplementationClassSameAsInterface_ThrowsUnresolvedClassException(): void
    {
        $container = new InterfaceContainer(FakeClassNoConstructor::class);

        self::assertUnresolvedClassException(
            FakeClassNoConstructor::class,
            fn () => $container->get(FakeClassNoConstructor::class)
        );
    }

    public function testGet_WithImplementationClassNotInstanceOfInterface_ThrowsUnresolvedClassException(): void
    {
        $container = new InterfaceContainer(FakeClassNoConstructor::class);

        self::assertUnresolvedClassException(
            FakeClassUsingContexts::class,
            fn () => $container->get(FakeClassUsingContexts::class)
        );
    }

    public function testHas_WithSubclassOfInterface_ReturnsTrue(): void
    {
        $container = new InterfaceContainer(FakeClassNoConstructor::class);

        self::assertTrue($container->has(FakeClassExtendsNoConstructor::class));
    }

    public function testHas_WithSameClassAsInterface_ReturnsFalse(): void
    {
        $container = new InterfaceContainer(FakeClassNoConstructor::class);

        self::assertFalse($container->has(FakeClassNoConstructor::class));
    }

    public function testHas_WithImplementationNotSubclassOfInterface_ReturnsFalse(): void
    {
        $container = new InterfaceContainer(FakeClassNoConstructor::class);

        self::assertFalse($container->has(FakeClassUsingContexts::class));
    }
}

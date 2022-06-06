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

use FiveTwo\DependencyInjection\InstanceProvision\InstanceProvider;
use FiveTwo\DependencyInjection\Lifetime\LifetimeStrategy;

/**
 * Interface for building a dependency container.
 */
interface ContainerBuilderInterface
{
    /**
     * Adds an instance factory with a lifetime strategy to the container for a given class.
     *
     * @template TClass of object
     *
     * @param class-string<TClass> $className The name of the class to add
     * @param LifetimeStrategy<TClass> $lifetimeStrategy The lifetime strategy to use to manage instances
     * @param InstanceProvider<TClass> $instanceProvider The instance factory to use to create new instances
     *
     * @return $this
     */
    public function add(
        string $className,
        LifetimeStrategy $lifetimeStrategy,
        InstanceProvider $instanceProvider
    ): static;

    /**
     * Adds a nested container with a factory for generating lifetime strategies to manage instances within the outer
     * container. Nested containers are searched sequentially in the order they are added.
     *
     * @param ContainerInterface $container The nested container to add
     * @param callable(class-string):LifetimeStrategy $lifetimeStrategyFactory A factory method for generating lifetime
     * strategies to manage instances within the container being built
     *
     * @return $this
     *
     * @phpstan-ignore-next-line PHPStan does not support callable-level generics but complains that LifetimeStrategy
     * does not have its generic type specified
     */
    public function addContainer(ContainerInterface $container, callable $lifetimeStrategyFactory): static;
}

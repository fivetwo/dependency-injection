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

use Throwable;

/**
 * Interface for exceptions that indicate a dependency could not be resolved because it contains a circular dependency.
 *
 * @template TClass of object
 */
interface CircularExceptionInterface extends Throwable
{
    /**
     * @return class-string<TClass> The class name of the dependency that could not be resolved due to circular
     * dependency
     * @psalm-mutation-free
     */
    public function getClassName(): string;
}

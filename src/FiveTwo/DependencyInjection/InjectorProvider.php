<?php
/*
 * Copyright (c) 2022 Five Two Foundation, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace FiveTwo\DependencyInjection;

interface InjectorProvider
{
    public function getInjector(): InjectorInterface;
}

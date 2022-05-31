<?php
/*
 * Copyright (c) 2022 Matthew Suhocki. All rights reserved.
 * Copyright (c) 2022 Five Two Foundation, LLC. All rights reserved.
 *
 * This software is licensed under the terms of the MIT License <https://opensource.org/licenses/MIT>.
 * The above copyright notice and this notice shall be included in all copies or substantial portions of this software.
 */

declare(strict_types=1);

namespace FiveTwo\DependencyInjection\Context;

use FiveTwo\DependencyInjection\DependencyInjectionException;
use FiveTwo\DependencyInjection\InjectorHelper;
use FiveTwo\DependencyInjection\InjectorInterface;
use FiveTwo\DependencyInjection\InjectorTrait;
use ReflectionAttribute;
use ReflectionMethod;
use ReflectionParameter;
use Throwable;

/**
 * Provides context-aware methods for injecting dependencies into function and constructor calls.
 *
 * @template TContainer of \FiveTwo\DependencyInjection\ContainerInterface
 */
class ContextInjector implements InjectorInterface
{
    use InjectorTrait;

    /**
     * @param ContextContainer<TContainer> $container The container from which dependencies will be resolved
     */
    public function __construct(
        private readonly ContextContainer $container
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function tryResolveParameter(ReflectionParameter $rParam, mixed &$paramValue): bool
    {
        $contextCount = 0;

        try {
            $rFunction = $rParam->getDeclaringFunction();

            if ($rFunction instanceof ReflectionMethod) {
                $contextCount += self::addAttributes($rFunction->getDeclaringClass()->getAttributes(Context::class));
            }

            /** @psalm-suppress ArgumentTypeCoercion Psalm missing stub for ReflectionFunctionAbstract */
            $contextCount += self::addAttributes($rFunction->getAttributes(Context::class));
            $contextCount += self::addAttributes($rParam->getAttributes(Context::class));

            return InjectorHelper::getInstanceFromParameter($this->container, $rParam, $paramValue);
        } finally {
            while (--$contextCount >= 0) {
                $this->container->pop();
            }
        }
    }

    /**
     * @param array<ReflectionAttribute<Context>> $rAttributes
     *
     * @return int the number of contexts pushed onto the stack
     */
    private function addAttributes(array $rAttributes): int
    {
        $count = 0;

        foreach ($rAttributes as $rAttribute) {
            try {
                foreach ($rAttribute->newInstance()->getNames() as $contextName) {
                    $this->container->push($contextName);
                    $count++;
                }
            } catch (Throwable $e) {
                while (--$count >= 0) {
                    $this->container->pop();
                }

                throw new DependencyInjectionException('Error parsing context attribute for parameter', $e);
            }
        }

        return $count;
    }
}

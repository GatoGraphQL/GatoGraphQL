<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializerInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterObjectSerializerCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return ObjectSerializationManagerInterface::class;
    }
    protected function getServiceClass(): string
    {
        return ObjectSerializerInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addObjectSerializer';
    }
}

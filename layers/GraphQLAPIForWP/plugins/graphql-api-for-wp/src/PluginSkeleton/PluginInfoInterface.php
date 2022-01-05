<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

interface PluginInfoInterface
{
    public function get(string $key): mixed;
    
    public function getVersion(): string;
    public function getFile(): string;
    public function getBaseName(): string;
    public function getName(): string;
    public function getDir(): string;
    public function getURL(): string;
}

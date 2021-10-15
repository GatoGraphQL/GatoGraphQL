<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface TypeInterface
{
    public function getID(): string;

    public function getKind(): string;

    public function getName(): string;

    public function getDescription(): ?string;
}

<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface TypeInterface extends SchemaDefinitionReferenceObjectInterface
{
    public function getKind(): string;

    public function getNamespacedName(): string;

    public function getElementName(): string;

    public function getName(): string;

    public function getDescription(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getExtensions(): array;
}

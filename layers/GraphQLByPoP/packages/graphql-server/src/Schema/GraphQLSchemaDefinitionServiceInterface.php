<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\Engine\Schema\SchemaDefinitionServiceInterface;

interface GraphQLSchemaDefinitionServiceInterface extends SchemaDefinitionServiceInterface
{
    public function getRootOrQueryRootTypeSchemaKey(): string;
    public function getRootOrQueryRootTypeResolverClass(): string;
    public function getRootOrMutationRootTypeSchemaKey(): ?string;
    public function getRootOrMutationRootTypeResolverClass(): ?string;
    public function getQueryRootTypeSchemaKey(): string;
    public function getQueryRootTypeResolverClass(): string;
    public function getMutationRootTypeSchemaKey(): ?string;
    public function getMutationRootTypeResolverClass(): ?string;
    public function getSubscriptionRootTypeSchemaKey(): ?string;
    public function getSubscriptionRootTypeResolverClass(): ?string;
}

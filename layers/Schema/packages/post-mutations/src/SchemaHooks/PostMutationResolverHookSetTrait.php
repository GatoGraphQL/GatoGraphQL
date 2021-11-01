<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\SchemaHooks;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait PostMutationResolverHookSetTrait
{
    abstract protected function getRootObjectTypeResolver(): RootObjectTypeResolver;
    abstract protected function getMutationRootObjectTypeResolver(): MutationRootObjectTypeResolver;
    abstract protected function getPostObjectTypeResolver(): PostObjectTypeResolver;

    protected function mustAddFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool {
        $isRootMutationType =
            $objectTypeResolver === $this->getRootObjectTypeResolver()
            || $objectTypeResolver === $this->getMutationRootObjectTypeResolver();
        return
            ($isRootMutationType && $fieldName === 'createPost')
            || ($isRootMutationType && $fieldName === 'updatePost')
            || ($objectTypeResolver === $this->getPostObjectTypeResolver() && $fieldName === 'update');
    }
}

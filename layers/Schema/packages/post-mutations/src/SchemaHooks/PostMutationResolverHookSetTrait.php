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
    protected RootObjectTypeResolver $rootObjectTypeResolver;
    protected MutationRootObjectTypeResolver $mutationRootObjectTypeResolver;
    protected PostObjectTypeResolver $postObjectTypeResolver;

    #[Required]
    public function autowirePostMutationResolverHookSetTrait(
        RootObjectTypeResolver $rootObjectTypeResolver,
        MutationRootObjectTypeResolver $mutationRootObjectTypeResolver,
        PostObjectTypeResolver $postObjectTypeResolver,
    ): void {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
        $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }

    protected function mustAddSchemaFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool {
        $isRootMutationType =
            $objectTypeResolver === $this->rootObjectTypeResolver
            || $objectTypeResolver === $this->mutationRootObjectTypeResolver;
        return
            ($isRootMutationType && $fieldName === 'createPost')
            || ($isRootMutationType && $fieldName === 'updatePost')
            || ($objectTypeResolver === $this->postObjectTypeResolver && $fieldName === 'update');
    }
}

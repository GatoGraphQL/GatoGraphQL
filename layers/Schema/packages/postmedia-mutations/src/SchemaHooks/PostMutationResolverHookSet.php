<?php

declare(strict_types=1);

namespace PoPSchema\PostMediaMutations\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPostMediaMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    protected RootObjectTypeResolver $rootObjectTypeResolver;
    protected PostObjectTypeResolver $postObjectTypeResolver;
    
    #[Required]
    public function autowirePostMutationResolverHookSet(
        RootObjectTypeResolver $rootObjectTypeResolver,
        PostObjectTypeResolver $postObjectTypeResolver,
    ): void {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }

    protected function mustAddSchemaFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool {
        return 
            ($objectTypeResolver === $this->rootObjectTypeResolver && $fieldName === 'createPost')
            || ($objectTypeResolver === $this->rootObjectTypeResolver && $fieldName === 'updatePost')
            || ($objectTypeResolver === $this->postObjectTypeResolver && $fieldName === 'update');
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPostMedia\FieldResolvers\InterfaceType\SupportingFeaturedImageInterfaceTypeFieldResolver;
use PoPSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI;
    protected SupportingFeaturedImageInterfaceTypeFieldResolver $supportingFeaturedImageInterfaceTypeFieldResolver;

    #[Required]
    public function autowireCustomPostObjectTypeFieldResolver(
        CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI,
        SupportingFeaturedImageInterfaceTypeFieldResolver $supportingFeaturedImageInterfaceTypeFieldResolver,
    ): void {
        $this->customPostMediaTypeAPI = $customPostMediaTypeAPI;
        $this->supportingFeaturedImageInterfaceTypeFieldResolver = $supportingFeaturedImageInterfaceTypeFieldResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->supportingFeaturedImageInterfaceTypeFieldResolver,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'hasFeaturedImage',
            'featuredImage',
        ];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $post = $object;
        switch ($fieldName) {
            case 'hasFeaturedImage':
                return $this->customPostMediaTypeAPI->hasCustomPostThumbnail($objectTypeResolver->getID($post));

            case 'featuredImage':
                return $this->customPostMediaTypeAPI->getCustomPostThumbnailID($objectTypeResolver->getID($post));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\UserMediaTypeAPIInterface;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class MediaUserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected UserMediaTypeAPIInterface $userMediaTypeAPI;
    protected UserObjectTypeResolver $userObjectTypeResolver;

    #[Required]
    public function autowireMediaUserObjectTypeFieldResolver(
        UserMediaTypeAPIInterface $userMediaTypeAPI,
        UserObjectTypeResolver $userObjectTypeResolver,
    ): void {
        $this->userMediaTypeAPI = $userMediaTypeAPI;
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'author',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'author' => $this->translationAPI->__('Media element\'s author', 'pop-media'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
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
        $media = $object;
        switch ($fieldName) {
            case 'author':
                return $this->userMediaTypeAPI->getMediaAuthorId($media);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'author':
                return $this->userObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}

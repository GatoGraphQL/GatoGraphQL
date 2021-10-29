<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;
use PoPSchema\Users\FieldResolvers\InterfaceType\WithAuthorInterfaceTypeFieldResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CustomPostUserTypeAPIInterface $customPostUserTypeAPI = null;
    private ?WithAuthorInterfaceTypeFieldResolver $withAuthorInterfaceTypeFieldResolver = null;

    public function setCustomPostUserTypeAPI(CustomPostUserTypeAPIInterface $customPostUserTypeAPI): void
    {
        $this->customPostUserTypeAPI = $customPostUserTypeAPI;
    }
    protected function getCustomPostUserTypeAPI(): CustomPostUserTypeAPIInterface
    {
        return $this->customPostUserTypeAPI ??= $this->instanceManager->getInstance(CustomPostUserTypeAPIInterface::class);
    }
    public function setWithAuthorInterfaceTypeFieldResolver(WithAuthorInterfaceTypeFieldResolver $withAuthorInterfaceTypeFieldResolver): void
    {
        $this->withAuthorInterfaceTypeFieldResolver = $withAuthorInterfaceTypeFieldResolver;
    }
    protected function getWithAuthorInterfaceTypeFieldResolver(): WithAuthorInterfaceTypeFieldResolver
    {
        return $this->withAuthorInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(WithAuthorInterfaceTypeFieldResolver::class);
    }

    //#[Required]
    final public function autowireCustomPostObjectTypeFieldResolver(
        CustomPostUserTypeAPIInterface $customPostUserTypeAPI,
        WithAuthorInterfaceTypeFieldResolver $withAuthorInterfaceTypeFieldResolver,
    ): void {
        $this->customPostUserTypeAPI = $customPostUserTypeAPI;
        $this->withAuthorInterfaceTypeFieldResolver = $withAuthorInterfaceTypeFieldResolver;
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
            $this->getWithAuthorInterfaceTypeFieldResolver(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'author',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'author' => $this->translationAPI->__('The post\'s author', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
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
        switch ($fieldName) {
            case 'author':
                return $this->getCustomPostUserTypeAPI()->getAuthorID($object);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

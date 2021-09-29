<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\Users\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class CommentUserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected CommentTypeAPIInterface $commentTypeAPI;
    protected UserObjectTypeResolver $userObjectTypeResolver;

    #[Required]
    public function autowireCommentUserObjectTypeFieldResolver(
        CommentTypeAPIInterface $commentTypeAPI,
        UserObjectTypeResolver $userObjectTypeResolver,
    ): void {
        $this->commentTypeAPI = $commentTypeAPI;
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentObjectTypeResolver::class,
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
        $descriptions = [
            'author' => $this->translationAPI->__('Comment\'s author', 'comments'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
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
        $comment = $object;
        switch ($fieldName) {
            case 'author':
                return $this->commentTypeAPI->getCommentUserId($comment);
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

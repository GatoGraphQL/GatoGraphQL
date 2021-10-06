<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CommentUserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected CommentTypeAPIInterface $commentTypeAPI;
    protected UserObjectTypeResolver $userObjectTypeResolver;

    #[Required]
    final public function autowireCommentUserObjectTypeFieldResolver(
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

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'author' => $this->translationAPI->__('Comment\'s author', 'comments'),
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
            $comment = $object;
            switch ($fieldName) {
                case 'author':
                    return $this->commentTypeAPI->getCommentUserId($comment);
            }
    
            return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
        }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'author' => $this->userObjectTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}

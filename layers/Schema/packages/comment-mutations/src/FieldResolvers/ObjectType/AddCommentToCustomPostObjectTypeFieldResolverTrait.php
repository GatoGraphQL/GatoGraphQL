<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CommentMutations\ComponentConfiguration;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait AddCommentToCustomPostObjectTypeFieldResolverTrait
{
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected EmailScalarTypeResolver $emailScalarTypeResolver;
    protected URLScalarTypeResolver $urlScalarTypeResolver;

    #[Required]
    final public function autowireObjectTypeFieldResolverTrait(
        StringScalarTypeResolver $stringScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
        EmailScalarTypeResolver $emailScalarTypeResolver,
        URLScalarTypeResolver $urlScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }

    private function getAddCommentToCustomPostSchemaFieldArgNameResolvers(
        bool $addCustomPostID,
        bool $addParentCommentID,
    ): array {
        $schemaFieldArgNameResolvers = [
            MutationInputProperties::COMMENT => $this->stringScalarTypeResolver,
        ];
        if ($addParentCommentID) {
            $schemaFieldArgNameResolvers[MutationInputProperties::PARENT_COMMENT_ID] = $this->idScalarTypeResolver;
        }
        if ($addCustomPostID) {
            $schemaFieldArgNameResolvers[MutationInputProperties::CUSTOMPOST_ID] = $this->idScalarTypeResolver;
        }
        if (!ComponentConfiguration::mustUserBeLoggedInToAddComment()) {
            $schemaFieldArgNameResolvers[MutationInputProperties::AUTHOR_NAME] = $this->stringScalarTypeResolver;
            $schemaFieldArgNameResolvers[MutationInputProperties::AUTHOR_EMAIL] = $this->emailScalarTypeResolver;
            $schemaFieldArgNameResolvers[MutationInputProperties::AUTHOR_URL] = $this->urlScalarTypeResolver;
        }
        return $schemaFieldArgNameResolvers;
    }

    private function getAddCommentToCustomPostSchemaFieldArgDescription(
        string $fieldArgName,
    ): ?string {
        return match ($fieldArgName) {
            MutationInputProperties::COMMENT => $this->translationAPI->__('The comment to add', 'comment-mutations'),
            MutationInputProperties::PARENT_COMMENT_ID => $this->translationAPI->__('The ID of the parent comment', 'comment-mutations'),
            MutationInputProperties::CUSTOMPOST_ID => $this->translationAPI->__('The ID of the custom post to add a comment to', 'comment-mutations'),
            MutationInputProperties::AUTHOR_NAME => $this->translationAPI->__('The comment author\'s name', 'comment-mutations'),
            MutationInputProperties::AUTHOR_EMAIL => $this->translationAPI->__('The comment author\'s email', 'comment-mutations'),
            MutationInputProperties::AUTHOR_URL => $this->translationAPI->__('The comment author\'s site URL', 'comment-mutations'),
            default => null,
        };
    }

    private function getAddCommentToCustomPostSchemaFieldArgTypeModifiers(
        string $fieldArgName,
        bool $isParentCommentMandatory = false,
    ): int {
        return match ($fieldArgName) {
            MutationInputProperties::COMMENT
                => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::PARENT_COMMENT_ID
                => ($isParentCommentMandatory ? SchemaTypeModifiers::MANDATORY : 0),
            MutationInputProperties::CUSTOMPOST_ID
                => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::AUTHOR_NAME,
            MutationInputProperties::AUTHOR_EMAIL
                => (ComponentConfiguration::requireCommenterNameAndEmail() ? SchemaTypeModifiers::MANDATORY : 0),
            default
                => SchemaTypeModifiers::NONE,
        };
    }
}

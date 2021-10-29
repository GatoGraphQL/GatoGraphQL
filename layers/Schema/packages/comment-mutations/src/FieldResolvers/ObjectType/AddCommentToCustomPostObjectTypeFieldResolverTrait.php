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
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;

    public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    public function setEmailScalarTypeResolver(EmailScalarTypeResolver $emailScalarTypeResolver): void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        return $this->emailScalarTypeResolver ??= $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
    }
    public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }

    private function getAddCommentToCustomPostSchemaFieldArgNameTypeResolvers(
        bool $addCustomPostID,
        bool $addParentCommentID,
    ): array {
        $schemaFieldArgNameTypeResolvers = [
            MutationInputProperties::COMMENT => $this->getStringScalarTypeResolver(),
        ];
        if ($addParentCommentID) {
            $schemaFieldArgNameTypeResolvers[MutationInputProperties::PARENT_COMMENT_ID] = $this->getIdScalarTypeResolver();
        }
        if ($addCustomPostID) {
            $schemaFieldArgNameTypeResolvers[MutationInputProperties::CUSTOMPOST_ID] = $this->getIdScalarTypeResolver();
        }
        if (!ComponentConfiguration::mustUserBeLoggedInToAddComment()) {
            $schemaFieldArgNameTypeResolvers[MutationInputProperties::AUTHOR_NAME] = $this->getStringScalarTypeResolver();
            $schemaFieldArgNameTypeResolvers[MutationInputProperties::AUTHOR_EMAIL] = $this->getEmailScalarTypeResolver();
            $schemaFieldArgNameTypeResolvers[MutationInputProperties::AUTHOR_URL] = $this->getUrlScalarTypeResolver();
        }
        return $schemaFieldArgNameTypeResolvers;
    }

    private function getAddCommentToCustomPostSchemaFieldArgDescription(
        string $fieldArgName,
    ): ?string {
        return match ($fieldArgName) {
            MutationInputProperties::COMMENT => $this->getTranslationAPI()->__('The comment to add', 'comment-mutations'),
            MutationInputProperties::PARENT_COMMENT_ID => $this->getTranslationAPI()->__('The ID of the parent comment', 'comment-mutations'),
            MutationInputProperties::CUSTOMPOST_ID => $this->getTranslationAPI()->__('The ID of the custom post to add a comment to', 'comment-mutations'),
            MutationInputProperties::AUTHOR_NAME => $this->getTranslationAPI()->__('The comment author\'s name', 'comment-mutations'),
            MutationInputProperties::AUTHOR_EMAIL => $this->getTranslationAPI()->__('The comment author\'s email', 'comment-mutations'),
            MutationInputProperties::AUTHOR_URL => $this->getTranslationAPI()->__('The comment author\'s site URL', 'comment-mutations'),
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

<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\TypeResolvers\InputObjectType;

use PoP\Root\Managers\ComponentManager;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CommentMutations\Component;
use PoPSchema\CommentMutations\ComponentConfiguration;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

abstract class AbstractAddCommentToCustomPostFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setEmailScalarTypeResolver(EmailScalarTypeResolver $emailScalarTypeResolver): void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    final protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        return $this->emailScalarTypeResolver ??= $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return array_merge(
            [
                MutationInputProperties::COMMENT => $this->getStringScalarTypeResolver(),
            ],
            $this->addCustomPostInputField() ? [
                MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            ] : [],
            $this->addParentCommentInputField() ? [
                MutationInputProperties::PARENT_COMMENT_ID => $this->getIDScalarTypeResolver(),
            ] : [],
            !$componentConfiguration->mustUserBeLoggedInToAddComment() ? [
                MutationInputProperties::AUTHOR_NAME => $this->getStringScalarTypeResolver(),
                MutationInputProperties::AUTHOR_EMAIL => $this->getEmailScalarTypeResolver(),
                MutationInputProperties::AUTHOR_URL => $this->getURLScalarTypeResolver(),
            ] : [],
        );
    }

    abstract protected function addCustomPostInputField(): bool;
    abstract protected function addParentCommentInputField(): bool;
    abstract protected function isParentCommentInputFieldMandatory(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::COMMENT => $this->getTranslationAPI()->__('The comment to add', 'comment-mutations'),
            MutationInputProperties::PARENT_COMMENT_ID => $this->getTranslationAPI()->__('The ID of the parent comment', 'comment-mutations'),
            MutationInputProperties::CUSTOMPOST_ID => $this->getTranslationAPI()->__('The ID of the custom post to add a comment to', 'comment-mutations'),
            MutationInputProperties::AUTHOR_NAME => $this->getTranslationAPI()->__('The comment author\'s name', 'comment-mutations'),
            MutationInputProperties::AUTHOR_EMAIL => $this->getTranslationAPI()->__('The comment author\'s email', 'comment-mutations'),
            MutationInputProperties::AUTHOR_URL => $this->getTranslationAPI()->__('The comment author\'s site URL', 'comment-mutations'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::COMMENT => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::PARENT_COMMENT_ID => $this->isParentCommentInputFieldMandatory() ? SchemaTypeModifiers::MANDATORY : SchemaTypeModifiers::NONE,
            MutationInputProperties::CUSTOMPOST_ID => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}

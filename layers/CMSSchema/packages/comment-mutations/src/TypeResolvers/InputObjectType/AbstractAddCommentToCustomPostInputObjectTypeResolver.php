<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

abstract class AbstractAddCommentToCustomPostInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CommentAsOneofInputObjectTypeResolver $commentAsOneofInputObjectTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        if ($this->emailScalarTypeResolver === null) {
            /** @var EmailScalarTypeResolver */
            $emailScalarTypeResolver = $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
            $this->emailScalarTypeResolver = $emailScalarTypeResolver;
        }
        return $this->emailScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        if ($this->urlScalarTypeResolver === null) {
            /** @var URLScalarTypeResolver */
            $urlScalarTypeResolver = $this->instanceManager->getInstance(URLScalarTypeResolver::class);
            $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        }
        return $this->urlScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getCommentAsOneofInputObjectTypeResolver(): CommentAsOneofInputObjectTypeResolver
    {
        if ($this->commentAsOneofInputObjectTypeResolver === null) {
            /** @var CommentAsOneofInputObjectTypeResolver */
            $commentAsOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CommentAsOneofInputObjectTypeResolver::class);
            $this->commentAsOneofInputObjectTypeResolver = $commentAsOneofInputObjectTypeResolver;
        }
        return $this->commentAsOneofInputObjectTypeResolver;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return array_merge(
            [
                MutationInputProperties::COMMENT_AS => $this->getCommentAsOneofInputObjectTypeResolver(),
            ],
            $this->addCustomPostInputField() ? [
                MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            ] : [],
            $this->addParentCommentInputField() ? [
                MutationInputProperties::PARENT_COMMENT_ID => $this->getIDScalarTypeResolver(),
            ] : [],
            !$moduleConfiguration->mustUserBeLoggedInToAddComment() ? [
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
            MutationInputProperties::COMMENT_AS => $this->__('The comment to add', 'comment-mutations'),
            MutationInputProperties::PARENT_COMMENT_ID => $this->__('The ID of the parent comment', 'comment-mutations'),
            MutationInputProperties::CUSTOMPOST_ID => $this->__('The ID of the custom post to add a comment to', 'comment-mutations'),
            MutationInputProperties::AUTHOR_NAME => $this->__('The comment author\'s name', 'comment-mutations'),
            MutationInputProperties::AUTHOR_EMAIL => $this->__('The comment author\'s email', 'comment-mutations'),
            MutationInputProperties::AUTHOR_URL => $this->__('The comment author\'s site URL', 'comment-mutations'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::COMMENT_AS => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::PARENT_COMMENT_ID => $this->isParentCommentInputFieldMandatory() ? SchemaTypeModifiers::MANDATORY : SchemaTypeModifiers::NONE,
            MutationInputProperties::CUSTOMPOST_ID => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}

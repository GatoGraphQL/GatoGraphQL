<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

abstract class AbstractCreateMediaItemInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CommentAsOneofInputObjectTypeResolver $commentAsOneofInputObjectTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final public function setEmailScalarTypeResolver(EmailScalarTypeResolver $emailScalarTypeResolver): void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
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
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
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
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
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
    final public function setCommentAsOneofInputObjectTypeResolver(CommentAsOneofInputObjectTypeResolver $commentAsOneofInputObjectTypeResolver): void
    {
        $this->commentAsOneofInputObjectTypeResolver = $commentAsOneofInputObjectTypeResolver;
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
            MutationInputProperties::COMMENT_AS => $this->__('The comment to add', 'media-mutations'),
            MutationInputProperties::PARENT_COMMENT_ID => $this->__('The ID of the parent comment', 'media-mutations'),
            MutationInputProperties::CUSTOMPOST_ID => $this->__('The ID of the custom post to add a comment to', 'media-mutations'),
            MutationInputProperties::AUTHOR_NAME => $this->__('The comment author\'s name', 'media-mutations'),
            MutationInputProperties::AUTHOR_EMAIL => $this->__('The comment author\'s email', 'media-mutations'),
            MutationInputProperties::AUTHOR_URL => $this->__('The comment author\'s site URL', 'media-mutations'),
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

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use DateTime;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType\IsCustomPostInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?DateFormatterInterface $dateFormatter = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;
    private ?IsCustomPostInterfaceTypeFieldResolver $isCustomPostInterfaceTypeFieldResolver = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    final public function setDateFormatter(DateFormatterInterface $dateFormatter): void
    {
        $this->dateFormatter = $dateFormatter;
    }
    final protected function getDateFormatter(): DateFormatterInterface
    {
        return $this->dateFormatter ??= $this->instanceManager->getInstance(DateFormatterInterface::class);
    }
    final public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        return $this->queryableInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
    }
    final public function setIsCustomPostInterfaceTypeFieldResolver(IsCustomPostInterfaceTypeFieldResolver $isCustomPostInterfaceTypeFieldResolver): void
    {
        $this->isCustomPostInterfaceTypeFieldResolver = $isCustomPostInterfaceTypeFieldResolver;
    }
    final protected function getIsCustomPostInterfaceTypeFieldResolver(): IsCustomPostInterfaceTypeFieldResolver
    {
        return $this->isCustomPostInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(IsCustomPostInterfaceTypeFieldResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getQueryableInterfaceTypeFieldResolver(),
            $this->getIsCustomPostInterfaceTypeFieldResolver(),
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPostTypeAPI = $this->getCustomPostTypeAPI();
        $customPost = $object;
        switch ($field->getName()) {
            case 'url':
                return $customPostTypeAPI->getPermalink($customPost);

            case 'urlAbsolutePath':
                return $customPostTypeAPI->getPermalinkPath($customPost);

            case 'slug':
                return $customPostTypeAPI->getSlug($customPost);

            case 'content':
                return $customPostTypeAPI->getContent($customPost);

            case 'rawContent':
                return $customPostTypeAPI->getRawContent($customPost);

            case 'status':
                return $customPostTypeAPI->getStatus($customPost);

            case 'isStatus':
                return $field->getArgument('status')?->getValue() == $customPostTypeAPI->getStatus($customPost);

            case 'date':
                return new DateTime($customPostTypeAPI->getPublishedDate($customPost, $field->getArgument('gmt')?->getValue()));

            case 'dateStr':
                return $this->getDateFormatter()->format(
                    $field->getArgument('format')?->getValue(),
                    $customPostTypeAPI->getPublishedDate($customPost, $field->getArgument('gmt')?->getValue())
                );

            case 'modifiedDate':
                return new DateTime($customPostTypeAPI->getModifiedDate($customPost, $field->getArgument('gmt')?->getValue()));

            case 'modifiedDateStr':
                return $this->getDateFormatter()->format(
                    $field->getArgument('format')?->getValue(),
                    $customPostTypeAPI->getModifiedDate($customPost, $field->getArgument('gmt')?->getValue())
                );

            case 'title':
                return $customPostTypeAPI->getTitle($customPost);

            case 'excerpt':
                return $customPostTypeAPI->getExcerpt($customPost);

            case 'customPostType':
                return $customPostTypeAPI->getCustomPostType($customPost);
        }

        return parent::resolveValue($objectTypeResolver, $object, $field, $objectTypeFieldResolutionFeedbackStore);
    }
}

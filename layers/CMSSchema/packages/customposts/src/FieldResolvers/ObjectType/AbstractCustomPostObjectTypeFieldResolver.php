<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType;

use DateTime;
use PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType\CustomPostInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?DateFormatterInterface $dateFormatter = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;
    private ?CustomPostInterfaceTypeFieldResolver $customPostInterfaceTypeFieldResolver = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    final public function setDateFormatter(DateFormatterInterface $dateFormatter): void
    {
        $this->dateFormatter = $dateFormatter;
    }
    final protected function getDateFormatter(): DateFormatterInterface
    {
        /** @var DateFormatterInterface */
        return $this->dateFormatter ??= $this->instanceManager->getInstance(DateFormatterInterface::class);
    }
    final public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        /** @var QueryableInterfaceTypeFieldResolver */
        return $this->queryableInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
    }
    final public function setCustomPostInterfaceTypeFieldResolver(CustomPostInterfaceTypeFieldResolver $customPostInterfaceTypeFieldResolver): void
    {
        $this->customPostInterfaceTypeFieldResolver = $customPostInterfaceTypeFieldResolver;
    }
    final protected function getCustomPostInterfaceTypeFieldResolver(): CustomPostInterfaceTypeFieldResolver
    {
        /** @var CustomPostInterfaceTypeFieldResolver */
        return $this->customPostInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(CustomPostInterfaceTypeFieldResolver::class);
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getQueryableInterfaceTypeFieldResolver(),
            $this->getCustomPostInterfaceTypeFieldResolver(),
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPostTypeAPI = $this->getCustomPostTypeAPI();
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'url':
                return $customPostTypeAPI->getPermalink($customPost);

            case 'urlPath':
                /** @var string */
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
                return $fieldDataAccessor->getValue('status') === $customPostTypeAPI->getStatus($customPost);

            case 'date':
                /** @var string */
                $date = $customPostTypeAPI->getPublishedDate($customPost, $fieldDataAccessor->getValue('gmt') ?? false);
                return new DateTime($date);

            case 'dateStr':
                /** @var string */
                $date = $customPostTypeAPI->getPublishedDate($customPost, $fieldDataAccessor->getValue('gmt') ?? false);
                return $this->getDateFormatter()->format(
                    $fieldDataAccessor->getValue('format'),
                    $date
                );

            case 'modifiedDate':
                /** @var string */
                $modifiedDate = $customPostTypeAPI->getModifiedDate($customPost, $fieldDataAccessor->getValue('gmt') ?? false);
                return new DateTime($modifiedDate);

            case 'modifiedDateStr':
                /** @var string */
                $modifiedDate = $customPostTypeAPI->getModifiedDate($customPost, $fieldDataAccessor->getValue('gmt') ?? false);
                return $this->getDateFormatter()->format(
                    $fieldDataAccessor->getValue('format'),
                    $modifiedDate
                );

            case 'title':
                return $customPostTypeAPI->getTitle($customPost);

            case 'excerpt':
                return $customPostTypeAPI->getExcerpt($customPost);

            case 'customPostType':
                /** @var string */
                $customPostType = $customPostTypeAPI->getCustomPostType($customPost);
                return $customPostType;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}

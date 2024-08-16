<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\FieldResolvers\ObjectType;

use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use WP_Post;

class QueryableMediaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CMSHelperServiceInterface $cmsHelperService = null;
    private ?DateFormatterInterface $dateFormatter = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;

    final public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        if ($this->cmsHelperService === null) {
            /** @var CMSHelperServiceInterface */
            $cmsHelperService = $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
            $this->cmsHelperService = $cmsHelperService;
        }
        return $this->cmsHelperService;
    }
    final public function setDateFormatter(DateFormatterInterface $dateFormatter): void
    {
        $this->dateFormatter = $dateFormatter;
    }
    final protected function getDateFormatter(): DateFormatterInterface
    {
        if ($this->dateFormatter === null) {
            /** @var DateFormatterInterface */
            $dateFormatter = $this->instanceManager->getInstance(DateFormatterInterface::class);
            $this->dateFormatter = $dateFormatter;
        }
        return $this->dateFormatter;
    }
    final public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        if ($this->queryableInterfaceTypeFieldResolver === null) {
            /** @var QueryableInterfaceTypeFieldResolver */
            $queryableInterfaceTypeFieldResolver = $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
            $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
        }
        return $this->queryableInterfaceTypeFieldResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaObjectTypeResolver::class,
        ];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getQueryableInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->__('Media element URL', 'pop-media'),
            'urlPath' => $this->__('Media element URL path', 'pop-media'),
            'slug' => $this->__('Media element slug', 'pop-media'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var WP_Post */
        $mediaItem = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'url':
            case 'urlPath':
                $url = \get_permalink($mediaItem->ID);
                if ($url === false) {
                    return '';
                }
                if ($fieldDataAccessor->getFieldName() === 'url') {
                    return $url;
                }
                /** @var string */
                return $this->getCMSHelperService()->getLocalURLPath($url);
            case 'slug':
                return $mediaItem->post_name;
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

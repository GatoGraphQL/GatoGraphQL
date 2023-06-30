<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Facades\Formatters\DateFormatterFacade;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType\CustomPostInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CustomPostInterfaceTypeFieldResolver $customPostInterfaceTypeFieldResolver = null;
    
    final public function setCustomPostInterfaceTypeFieldResolver(CustomPostInterfaceTypeFieldResolver $customPostInterfaceTypeFieldResolver): void
    {
        $this->customPostInterfaceTypeFieldResolver = $customPostInterfaceTypeFieldResolver;
    }
    final protected function getCustomPostInterfaceTypeFieldResolver(): CustomPostInterfaceTypeFieldResolver
    {
        if ($this->customPostInterfaceTypeFieldResolver === null) {
            /** @var CustomPostInterfaceTypeFieldResolver */
            $customPostInterfaceTypeFieldResolver = $this->instanceManager->getInstance(CustomPostInterfaceTypeFieldResolver::class);
            $this->customPostInterfaceTypeFieldResolver = $customPostInterfaceTypeFieldResolver;
        }
        return $this->customPostInterfaceTypeFieldResolver;
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
            $this->customPostInterfaceTypeFieldResolver,
        ];
    }

    protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI;
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $dateFormatter = DateFormatterFacade::getInstance();
        $customPostTypeAPI = $this->getCustomPostTypeAPI();
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'datetime':
                // If it is the current year, don't add the year. Otherwise, do
                // 15 Jul, 21:47 or // 15 Jul 2018, 21:47
                $date = $customPostTypeAPI->getPublishedDate($customPost);
                $format = $fieldDataAccessor->getValue('format');
                if (!$format) {
                    $format = ($dateFormatter->format('Y', $date) === date('Y')) ? 'j M, H:i' : 'j M Y, H:i';
                }
                return $dateFormatter->format($format, $date);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

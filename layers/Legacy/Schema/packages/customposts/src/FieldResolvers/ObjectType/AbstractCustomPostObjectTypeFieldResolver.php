<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Facades\Formatters\DateFormatterFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType\IsCustomPostInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?IsCustomPostInterfaceTypeFieldResolver $isCustomPostInterfaceTypeFieldResolver = null;
    
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
            $this->isCustomPostInterfaceTypeFieldResolver,
        ];
    }

    protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        array $options = []
    ): mixed {
        $dateFormatter = DateFormatterFacade::getInstance();
        $customPostTypeAPI = $this->getCustomPostTypeAPI();
        $customPost = $object;
        switch ($fieldName) {
            case 'datetime':
                // If it is the current year, don't add the year. Otherwise, do
                // 15 Jul, 21:47 or // 15 Jul 2018, 21:47
                $date = $customPostTypeAPI->getPublishedDate($customPost);
                $format = $fieldArgs['format'];
                if (!$format) {
                    $format = ($dateFormatter->format('Y', $date) == date('Y')) ? 'j M, H:i' : 'j M Y, H:i';
                }
                return $dateFormatter->format($format, $date);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

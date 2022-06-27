<?php

declare(strict_types=1);

namespace PoPCMSSchema\Meta\FieldResolvers\ObjectType;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Meta\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

abstract class AbstractWithMetaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver = null;

    final public function setWithMetaInterfaceTypeFieldResolver(WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver): void
    {
        $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
    }
    final protected function getWithMetaInterfaceTypeFieldResolver(): WithMetaInterfaceTypeFieldResolver
    {
        return $this->withMetaInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(WithMetaInterfaceTypeFieldResolver::class);
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getWithMetaInterfaceTypeFieldResolver(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'metaValue',
            'metaValues',
        ];
    }

    abstract protected function getMetaTypeAPI(): MetaTypeAPIInterface;

    protected function doResolveSchemaValidationErrors(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs
    ): array {
        // if (!FieldQueryUtils::isAnyFieldArgumentValueAField($fieldArgs)) {
        switch ($field->getName()) {
            case 'metaValue':
            case 'metaValues':
                if (!$this->getMetaTypeAPI()->validateIsMetaKeyAllowed($field->getArgument('key')?->getTEMPPPValue())) {
                    return [
                        new FeedbackItemResolution(
                            FeedbackItemProvider::class,
                            FeedbackItemProvider::E1,
                            [
                                $field->getArgument('key')?->getTEMPPPValue(),
                            ]
                        ),
                    ];
                }
                break;
        }
        // }

        return parent::doResolveSchemaValidationErrors($objectTypeResolver, $fieldName, $fieldArgs);
    }

    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
    ): bool {
        switch ($field->getName()) {
            case 'metaValue':
            case 'metaValues':
                return true;
        }
        return parent::validateResolvedFieldType(
            $objectTypeResolver,
            $fieldName,
            $fieldArgs,
        );
    }
}

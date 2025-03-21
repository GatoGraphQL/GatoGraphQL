<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoPCMSSchema\Meta\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\MetaMutations\FieldResolvers\InterfaceType\WithMetaMutationsInterfaceTypeFieldResolver;

abstract class AbstractWithMetaMutationsObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?WithMetaMutationsInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver = null;

    final protected function getWithMetaMutationsInterfaceTypeFieldResolver(): WithMetaMutationsInterfaceTypeFieldResolver
    {
        if ($this->withMetaInterfaceTypeFieldResolver === null) {
            /** @var WithMetaMutationsInterfaceTypeFieldResolver */
            $withMetaInterfaceTypeFieldResolver = $this->instanceManager->getInstance(WithMetaMutationsInterfaceTypeFieldResolver::class);
            $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
        }
        return $this->withMetaInterfaceTypeFieldResolver;
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getWithMetaMutationsInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'metaValue',
            'metaValues',
        ];
    }

    abstract protected function getMetaTypeAPI(): MetaTypeAPIInterface;

    /**
     * Custom validations
     */
    public function validateFieldKeyValues(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validateFieldKeyValues($objectTypeResolver, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                if (!$this->getMetaTypeAPI()->validateIsMetaKeyAllowed($fieldDataAccessor->getValue('key'))) {
                    $field = $fieldDataAccessor->getField();
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                FeedbackItemProvider::class,
                                FeedbackItemProvider::E1,
                                [
                                    $fieldDataAccessor->getValue('key'),
                                ]
                            ),
                            $field->getArgument('key') ?? $field,
                        )
                    );
                }
                break;
        }
    }
}

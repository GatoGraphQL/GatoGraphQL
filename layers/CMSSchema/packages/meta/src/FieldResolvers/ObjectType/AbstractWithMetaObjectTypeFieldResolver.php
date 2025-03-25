<?php

declare(strict_types=1);

namespace PoPCMSSchema\Meta\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoPCMSSchema\Meta\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

abstract class AbstractWithMetaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver = null;

    final protected function getWithMetaInterfaceTypeFieldResolver(): WithMetaInterfaceTypeFieldResolver
    {
        if ($this->withMetaInterfaceTypeFieldResolver === null) {
            /** @var WithMetaInterfaceTypeFieldResolver */
            $withMetaInterfaceTypeFieldResolver = $this->instanceManager->getInstance(WithMetaInterfaceTypeFieldResolver::class);
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
            $this->getWithMetaInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'metaKeys',
            'metaValue',
            'metaValues',
            'meta',
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
                $key = $fieldDataAccessor->getValue('key');
                if (!$this->getMetaTypeAPI()->validateIsMetaKeyAllowed($key)) {
                    $field = $fieldDataAccessor->getField();
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                FeedbackItemProvider::class,
                                FeedbackItemProvider::E1,
                                [
                                    $key,
                                ]
                            ),
                            $field->getArgument('key') ?? $field,
                        )
                    );
                }
                break;
            case 'meta':
                $nonAllowedKeys = [];
                $metaTypeAPI = $this->getMetaTypeAPI();
                /** @var string[] */
                $keys = $fieldDataAccessor->getValue('keys');
                foreach ($keys as $key) {
                    if ($metaTypeAPI->validateIsMetaKeyAllowed($key)) {
                        continue;
                    }
                    $nonAllowedKeys[] = $key;
                }
                if ($nonAllowedKeys !== []) {
                    $field = $fieldDataAccessor->getField();
                    if (count($nonAllowedKeys) === 1) {
                        $objectTypeFieldResolutionFeedbackStore->addError(
                            new ObjectTypeFieldResolutionFeedback(
                                new FeedbackItemResolution(
                                    FeedbackItemProvider::class,
                                    FeedbackItemProvider::E1,
                                    [
                                        $nonAllowedKeys[0],
                                    ]
                                ),
                                $field->getArgument('keys') ?? $field,
                            )
                        );
                        break;
                    }
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                FeedbackItemProvider::class,
                                FeedbackItemProvider::E2,
                                [
                                    implode(
                                        $this->__('\', \'', 'gatographql'),
                                        $nonAllowedKeys
                                    ),
                                ]
                            ),
                            $field->getArgument('keys') ?? $field,
                        )
                    );
                }
                break;
        }
    }
}

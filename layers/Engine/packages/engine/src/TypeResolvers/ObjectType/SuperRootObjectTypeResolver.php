<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\Registries\MandatoryOperationDirectiveResolverRegistryInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Engine\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\Engine\ObjectModels\SuperRoot;
use PoP\Engine\RelationalTypeDataLoaders\ObjectType\SuperRootObjectTypeDataLoader;
use PoP\Engine\StaticHelpers\SuperRootHelper;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

class SuperRootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;

    private ?SuperRootObjectTypeDataLoader $superRootObjectTypeDataLoader = null;
    private ?MandatoryOperationDirectiveResolverRegistryInterface $mandatoryOperationDirectiveResolverRegistry = null;

    final public function setSuperRootObjectTypeDataLoader(SuperRootObjectTypeDataLoader $superRootObjectTypeDataLoader): void
    {
        $this->superRootObjectTypeDataLoader = $superRootObjectTypeDataLoader;
    }
    final protected function getSuperRootObjectTypeDataLoader(): SuperRootObjectTypeDataLoader
    {
        if ($this->superRootObjectTypeDataLoader === null) {
            /** @var SuperRootObjectTypeDataLoader */
            $superRootObjectTypeDataLoader = $this->instanceManager->getInstance(SuperRootObjectTypeDataLoader::class);
            $this->superRootObjectTypeDataLoader = $superRootObjectTypeDataLoader;
        }
        return $this->superRootObjectTypeDataLoader;
    }
    final public function setMandatoryOperationDirectiveResolverRegistry(MandatoryOperationDirectiveResolverRegistryInterface $mandatoryOperationDirectiveResolverRegistry): void
    {
        $this->mandatoryOperationDirectiveResolverRegistry = $mandatoryOperationDirectiveResolverRegistry;
    }
    final protected function getMandatoryOperationDirectiveResolverRegistry(): MandatoryOperationDirectiveResolverRegistryInterface
    {
        if ($this->mandatoryOperationDirectiveResolverRegistry === null) {
            /** @var MandatoryOperationDirectiveResolverRegistryInterface */
            $mandatoryOperationDirectiveResolverRegistry = $this->instanceManager->getInstance(MandatoryOperationDirectiveResolverRegistryInterface::class);
            $this->mandatoryOperationDirectiveResolverRegistry = $mandatoryOperationDirectiveResolverRegistry;
        }
        return $this->mandatoryOperationDirectiveResolverRegistry;
    }

    public function getTypeName(): string
    {
        return 'SuperRoot';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('(Internal) Super Root type, starting from which the query is executed', 'engine');
    }

    public function getID(object $object): string|int|null
    {
        /** @var SuperRoot */
        $superRoot = $object;
        return $superRoot->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSuperRootObjectTypeDataLoader();
    }

    /**
     * Provide the mandatory directives for Operations.
     *
     * @return FieldDirectiveResolverInterface[]
     */
    protected function getMandatoryFieldOrOperationDirectiveResolvers(): array
    {
        return $this->getMandatoryOperationDirectiveResolverRegistry()->getMandatoryOperationDirectiveResolvers();
    }

    /**
     * Satisfy for Operation Directives
     */
    protected function getSupportedDirectiveLocationsByBehavior(): array
    {
        return [
            FieldDirectiveBehaviors::OPERATION,
            FieldDirectiveBehaviors::FIELD,
            FieldDirectiveBehaviors::FIELD_AND_OPERATION,
        ];
    }

    /**
     * Provide a different error message for the SuperRoot field,
     * as it represents an Operation and not a Field
     */
    public function getFieldNotResolvedByObjectTypeFeedbackItemResolution(
        FieldInterface $field,
    ): FeedbackItemResolution {
        $operation = SuperRootHelper::getOperationFromSuperRootFieldName($field->getName());
        if ($operation !== null) {
            return new FeedbackItemResolution(
                ErrorFeedbackItemProvider::class,
                ErrorFeedbackItemProvider::E1,
                [
                    $operation,
                ]
            );
        }
        return new FeedbackItemResolution(
            ErrorFeedbackItemProvider::class,
            ErrorFeedbackItemProvider::E1A
        );
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

class ExecutableDocument extends UpstreamExecutableDocument
{
    private ?TypeRegistryInterface $typeRegistry = null;

    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        return $this->typeRegistry ??= InstanceManagerFacade::getInstance()->getInstance(TypeRegistryInterface::class);
    }

    /**
     * @throws InvalidRequestException
     */
    protected function validate(): void
    {
        parent::validate();
        $this->assertFragmentSpreadTypesExistInSchema();
    }

    /**
     * @throws InvalidRequestException
     * @see https://spec.graphql.org/draft/#sec-Fragment-Spread-Type-Existence
     */
    protected function assertFragmentSpreadTypesExistInSchema(): void
    {
        $typeResolvers = [
            ...$this->getTypeRegistry()->getObjectTypeResolvers(),
            ...$this->getTypeRegistry()->getInterfaceTypeResolvers()
        ];
        foreach ($this->document->getFragments() as $fragment) {
            $fragmentModel = $fragment->getModel();
            foreach ($typeResolvers as $typeResolver) {
                if ($typeResolver->getTypeName() === $fragmentModel
                    || $typeResolver->getNamespacedTypeName() === $fragmentModel
                ) {
                    continue(2);
                }
            }
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_2,
                    [
                        $fragmentModel,
                    ]
                ),
                LocationHelper::getNonSpecificLocation()
            );
        }
    }
}

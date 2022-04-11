<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Execution\AbstractExecutableDocument;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

class ExecutableDocument extends AbstractExecutableDocument
{
    private ?TypeRegistryInterface $typeRegistry = null;

    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        return $this->typeRegistry ??= InstanceManagerFacade::getInstance()->getInstance(TypeRegistryInterface::class);
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertFragmentSpreadTypesExistInSchema(): void
    {
        foreach ($this->document->getFragments() as $fragment) {
            $fragmentModel = $fragment->getModel();
            if (false/*in_array($fragmentModel, $fragmentNames)*/) {
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
}

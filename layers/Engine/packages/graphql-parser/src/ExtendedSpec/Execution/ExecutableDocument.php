<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\Facades\Query\QueryAugmenterServiceFacade;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\DocumentFeedback;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\FeedbackItemProviders\SuggestionFeedbackItemProvider;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class ExecutableDocument extends UpstreamExecutableDocument
{
    /**
     * Override to support the "multiple query execution" feature:
     * If passing operationName `__ALL`, or passing no operationName
     * but there is an operation `__ALL` in the document,
     * then execute all operations (hack).
     *
     * @return OperationInterface[]
     */
    protected function assertAndGetRequestedOperations(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableMultipleQueryExecution()) {
            return parent::assertAndGetRequestedOperations();
        }

        if (count($this->document->getOperations()) === 1) {
            return parent::assertAndGetRequestedOperations();
        }

        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        $multipleQueryExecutionOperations = $queryAugmenterService->getMultipleQueryExecutionOperations($this->context->getOperationName(), $this->document->getOperations());
        if ($multipleQueryExecutionOperations !== null) {
            return $multipleQueryExecutionOperations;
        }

        // Add a suggestion indicating to pass __ALL in the query
        App::getFeedbackStore()->documentFeedbackStore->addSuggestion(
            new DocumentFeedback(
                new FeedbackItemResolution(
                    SuggestionFeedbackItemProvider::class,
                    SuggestionFeedbackItemProvider::S1
                ),
                LocationHelper::getNonSpecificLocation()
            )
        );

        return parent::assertAndGetRequestedOperations();
    }
}

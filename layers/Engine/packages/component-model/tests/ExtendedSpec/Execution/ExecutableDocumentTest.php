<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ExecutableDocumentTest as UpstreamExecutableDocumentTest;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocumentInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\Root\Feedback\FeedbackItemResolution;

class ExecutableDocumentTest extends UpstreamExecutableDocumentTest
{
    protected function createExecutableDocument(
      Document $document,
      Context $context,
    ): ExecutableDocumentInterface {
        return new ExecutableDocument($document, $context);
    }

    public function testExistingTypeFragmentSpread()
    {
        $parser = $this->getParser();
        $document = $parser->parse(
            <<<GRAPHQL
            {
                customPosts {
                    __typename
                    ...PostData
                }
            }
            
            fragment PostData on Post {
                id
                title
            }
            GRAPHQL
        );
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function testExistingInterfaceFragmentSpread()
    {
        $parser = $this->getParser();
        $document = $parser->parse(
            <<<GRAPHQL
            {
                customPosts {
                    __typename
                    ...PostData
                }
            }
            
            fragment PostData on IsCustomPost {
                id
                title
            }
            GRAPHQL
        );
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function testNonExistingTypeOrInterfaceFragmentSpread()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_2, ['ThisTypeDoesNotExist']))->getMessage());
        $parser = $this->getParser();
        $document = $parser->parse(
            <<<GRAPHQL
            {
                customPosts {
                    __typename
                    ...PostData
                }
            }
            
            fragment PostData on ThisTypeDoesNotExist {
                id
                title
            }
            GRAPHQL
        );
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
    }
}

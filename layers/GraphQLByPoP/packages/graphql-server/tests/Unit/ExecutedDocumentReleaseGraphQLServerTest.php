<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PoP\ComponentModel\App;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use WeakReference;

use function gc_collect_cycles;

/**
 * Services outlive the GraphQL queries they execute, so whatever they
 * cache under the AST of a query must not keep that AST alive once the
 * query is done. Otherwise a request executing many queries (as an
 * internal GraphQL server does) accumulates the AST of every one of
 * them, until it runs out of memory.
 */
class ExecutedDocumentReleaseGraphQLServerTest extends AbstractGraphQLServerTestCase
{
    public function testExecutedDocumentIsReleasedOnceTheNextOneIsExecuted(): void
    {
        $server = self::getGraphQLServer();
        $server->execute('
            {
                id @include(if: true)
                __typename
            }
        ');

        /** @var ExecutableDocument */
        $executableDocument = App::getState('executable-document-ast');
        $document = $executableDocument->getDocument();
        $operation = $document->getOperations()[0];
        /** @var FieldInterface */
        $field = $operation->getFieldsOrFragmentBonds()[0];
        /** @var Directive */
        $directive = $field->getDirectives()[0];

        $documentReference = WeakReference::create($document);
        $fieldReference = WeakReference::create($field);
        $directiveReference = WeakReference::create($directive);
        unset($executableDocument, $document, $operation, $field, $directive);

        $server->execute('
            {
                id
            }
        ');
        gc_collect_cycles();

        $this->assertNull($documentReference->get());
        $this->assertNull($fieldReference->get());
        $this->assertNull($directiveReference->get());
    }
}

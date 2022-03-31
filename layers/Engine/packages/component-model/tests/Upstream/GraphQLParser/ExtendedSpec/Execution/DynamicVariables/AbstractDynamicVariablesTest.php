<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\DynamicVariables;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\AbstractTestCase;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractDynamicVariablesTest extends AbstractTestCase
{
    /**
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    protected static function getComponentClassConfiguration(): array
    {
        $componentClassConfiguration = parent::getComponentClassConfiguration();
        $componentClassConfiguration[\PoP\GraphQLParser\Component::class][\PoP\GraphQLParser\Environment::ENABLE_DYNAMIC_VARIABLES] = static::enabled();
        return $componentClassConfiguration;
    }

    abstract protected static function enabled(): bool;

    protected function getParser(): ParserInterface
    {
        return $this->getService(ParserInterface::class);
    }

    protected function getQueryOperation(?Variable $variable, VariableReference $variableReference): QueryOperation
    {
        $variables = $variable !== null
            ? [
                $variable,
            ]
            : [];
        return new QueryOperation(
            'Op',
            $variables,
            [],
            [
                new RelationalField(
                    'film',
                    null,
                    [
                        new Argument('id', $variableReference, new Location(3, 22)),
                    ],
                    [
                        new LeafField('title', null, [], [], new Location(4, 21)),
                    ],
                    [],
                    new Location(3, 17)
                )
            ],
            new Location(2, 19)
        );
    }

    protected function getContext(array $variableValues): Context
    {
        return new Context('Op', $variableValues);
    }

    protected function getVariableName(
        bool $useDynamicVariableName,
    ): string {
        return $useDynamicVariableName ? '_id' : 'id';
    }

    protected function getQuery(
        bool $addVariableInOperation,
        bool $useDynamicVariableName,
    ): string {
        $variableName = '$' . $this->getVariableName($useDynamicVariableName);
        return sprintf(
            '
            query Op%s {
                film(id: %s) {
                    title
                }
            }
            ',
            $addVariableInOperation ? sprintf('(%s: ID)', $variableName) : '',
            $variableName
        );
    }

    protected function getVariable(string $variableName): Variable
    {
        return new Variable($variableName, 'ID', false, false, false, new Location(2, 22));
    }

    public function testVariableDefinedInOperationAndDynamicVariableName(): void
    {
        $addVariableInOperation = true;
        $useDynamicVariableName = true;
        $variableName = $this->getVariableName($useDynamicVariableName);
        $variable = $this->getVariable($variableName);
        $context = $this->getContext([
            $variableName => 1,
        ]);
        $variable->setContext($context);
        $variableReference = new VariableReference($variableName, $variable, new Location(3, 26));
        $executableDocument = new ExecutableDocument($this->getParser()->parse($this->getQuery($addVariableInOperation, $useDynamicVariableName)), $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $this->getQueryOperation($variable, $variableReference),
            ],
            $executableDocument->getRequestedOperations()
        );
    }

    public function testVariableDefinedInOperationAndStaticVariableName(): void
    {
        $addVariableInOperation = true;
        $useDynamicVariableName = false;
        $variableName = $this->getVariableName($useDynamicVariableName);
        $variable = $this->getVariable($variableName);
        $context = $this->getContext([
            $variableName => 1,
        ]);
        $variable->setContext($context);
        $variableReference = new VariableReference($variableName, $variable, new Location(3, 26));
        $executableDocument = new ExecutableDocument($this->getParser()->parse($this->getQuery($addVariableInOperation, $useDynamicVariableName)), $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $this->getQueryOperation($variable, $variableReference),
            ],
            $executableDocument->getRequestedOperations()
        );
    }

    public function testVariableNotDefinedInOperationAndDynamicVariableName(): void
    {
        $addVariableInOperation = false;
        $useDynamicVariableName = true;
        $variableName = $this->getVariableName($useDynamicVariableName);
        $variable = $this->getVariable($variableName);
        $context = $this->getContext([
            $variableName => 1,
        ]);
        $variable->setContext($context);
        $executableDocument = new ExecutableDocument($this->getParser()->parse($this->getQuery($addVariableInOperation, $useDynamicVariableName)), $context);
        if (!$this->enabled()) {
            $feedbackItemResolution = new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_8_3, [$variableName]);
            $this->expectException(InvalidRequestException::class);
            $this->expectExceptionMessage($feedbackItemResolution->getMessage());
        }
        $executableDocument->validateAndInitialize();
        if ($this->enabled()) {
            $variableReference = new DynamicVariableReference($variableName, new Location(3, 26));
            $variableReference->setContext($context);
            $this->assertEquals(
                [
                    $this->getQueryOperation(null, $variableReference),
                ],
                $executableDocument->getRequestedOperations()
            );
        }
    }

    public function testVariableNotDefinedInOperationAndStaticVariableName(): void
    {
        $addVariableInOperation = false;
        $useDynamicVariableName = false;
        $variableName = $this->getVariableName($useDynamicVariableName);
        $variable = $this->getVariable($variableName);
        $context = $this->getContext([
            $variableName => 1,
        ]);
        $variable->setContext($context);
        $executableDocument = new ExecutableDocument($this->getParser()->parse($this->getQuery($addVariableInOperation, $useDynamicVariableName)), $context);
        $feedbackItemResolution = new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_8_3, [$variableName]);
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage($feedbackItemResolution->getMessage());
        $executableDocument->validateAndInitialize();
    }
}

<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Rector\DowngradePhp70\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ThisType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\PhpDocDecorator\PhpDocFromTypeDeclarationDecorator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * This class was removed from Rector v0.17.2, and as it's still
 * used in this codebase, it was reinstated here.
 *
 * @source https://github.com/rectorphp/rector-downgrade-php/blob/160715ae11294985cc284500967c40d96ef49cfe/rules/DowngradePhp70/Rector/ClassMethod/DowngradeSelfTypeDeclarationRector.php
 *
 * @see https://github.com/rectorphp/rector-downgrade-php/pull/116
 */
final class DowngradeSelfTypeDeclarationRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocFromTypeDeclarationDecorator $phpDocFromTypeDeclarationDecorator,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove "self" return type, add a "@return $this" tag instead',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function foo(): self
    {
        return $this;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return $this
     */
    public function foo()
    {
        return $this;
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $thisType = new ThisType($classReflection);

        if (! $this->phpDocFromTypeDeclarationDecorator->decorateReturnWithSpecificType($node, $thisType)) {
            return null;
        }

        return $node;
    }
}

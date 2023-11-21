<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Rector\EarlyReturn\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\EarlyReturn\NodeAnalyzer\IfAndAnalyzer;
use Rector\EarlyReturn\NodeAnalyzer\SimpleScalarAnalyzer;
use Rector\EarlyReturn\NodeFactory\InvertedIfFactory;
use Rector\NodeCollector\BinaryOpConditionsCollector;
use Rector\NodeNestingScope\ContextAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\ChangeAndIfToEarlyReturnRectorTest
 */
final class ChangeIfOrReturnToEarlyReturnRector extends AbstractRector
{
    public function __construct(
        private readonly IfManipulator $ifManipulator,
        private readonly InvertedIfFactory $invertedIfFactory,
        private readonly ContextAnalyzer $contextAnalyzer,
        private readonly BinaryOpConditionsCollector $binaryOpConditionsCollector,
        private readonly SimpleScalarAnalyzer $simpleScalarAnalyzer,
        private readonly IfAndAnalyzer $ifAndAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changes if || return to early return', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function canDrive(Car $car)
    {
        if ($car->hasWheels || $car->hasFuel) {
            return true;
        }

        return false;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function canDrive(Car $car)
    {
        if ($car->hasWheels) {
            return true;
        }

        if ($car->hasFuel) {
            return true;
        }

        return false;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            ClassMethod::class,
            Function_::class,
            Foreach_::class,
            Closure::class,
            FileWithoutNamespace::class,
            Namespace_::class,
        ];
    }

    /**
     * @param Stmt\ClassMethod|Stmt\Function_|Stmt\Foreach_|Expr\Closure|FileWithoutNamespace|Stmt\Namespace_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $stmts = (array) $node->stmts;
        if ($stmts === []) {
            return null;
        }

        $newStmts = [];

        foreach ($stmts as $key => $stmt) {
            if (! $stmt instanceof If_) {
                // keep natural original order
                $newStmts[] = $stmt;
                continue;
            }

            $nextStmt = $stmts[$key + 1] ?? null;

            if ($this->shouldSkip($stmt, $nextStmt)) {
                $newStmts[] = $stmt;
                continue;
            }

            if ($nextStmt instanceof Return_) {
                if ($this->ifAndAnalyzer->isIfStmtExprUsedInNextReturn($stmt, $nextStmt)) {
                    continue;
                }

                if ($nextStmt->expr instanceof BooleanAnd) {
                    continue;
                }
            }

            /** @var BooleanAnd $expr */
            $expr = $stmt->cond;
            $booleanAndConditions = $this->binaryOpConditionsCollector->findConditions($expr, BooleanAnd::class);
            $afterStmts = [];

            if (! $nextStmt instanceof Return_) {
                $afterStmts[] = $stmt->stmts[0];

                $node->stmts = [
                    ...$newStmts,
                    ...$this->processReplaceIfs($stmt, $booleanAndConditions, new Return_(), $afterStmts, $nextStmt),
                ];

                return $node;
            }

            // remove next node
            unset($newStmts[$key + 1]);

            $afterStmts[] = $stmt->stmts[0];

            $ifNextReturnClone = $stmt->stmts[0] instanceof Return_
                ? clone $stmt->stmts[0]
                : new Return_();

            if ($this->isInLoopWithoutContinueOrBreak($stmt)) {
                $afterStmts[] = new Return_();
            }

            $changedStmts = $this->processReplaceIfs(
                $stmt,
                $booleanAndConditions,
                $ifNextReturnClone,
                $afterStmts,
                $nextStmt
            );

            // update stmts
            $node->stmts = [...$newStmts, ...$changedStmts];

            return $node;
        }

        return null;
    }

    private function isInLoopWithoutContinueOrBreak(If_ $if): bool
    {
        if (! $this->contextAnalyzer->isInLoop($if)) {
            return false;
        }

        if ($if->stmts[0] instanceof Continue_) {
            return false;
        }

        return ! $if->stmts[0] instanceof Break_;
    }

    /**
     * @param Expr[] $conditions
     * @param Stmt[] $afters
     * @return Stmt[]
     */
    private function processReplaceIfs(
        If_ $if,
        array $conditions,
        Return_ $ifNextReturn,
        array $afters,
        ?Stmt $nextStmt
    ): array {
        $ifs = $this->invertedIfFactory->createFromConditions($if, $conditions, $ifNextReturn, $nextStmt);
        $this->mirrorComments($ifs[0], $if);

        $result = [...$ifs, ...$afters];
        if ($if->stmts[0] instanceof Return_) {
            return $result;
        }

        if (! $ifNextReturn->expr instanceof Expr) {
            return $result;
        }

        if ($this->contextAnalyzer->isInLoop($if)) {
            return $result;
        }

        return [...$result, $ifNextReturn];
    }

    private function shouldSkip(If_ $if, ?Stmt $nexStmt): bool
    {
        if (! $this->ifManipulator->isIfWithOnlyOneStmt($if)) {
            return true;
        }

        if (! $if->cond instanceof BooleanAnd) {
            return true;
        }

        if (! $this->ifManipulator->isIfWithoutElseAndElseIfs($if)) {
            return true;
        }

        // is simple return? skip it
        $onlyStmt = $if->stmts[0];
        if ($onlyStmt instanceof Return_ && $onlyStmt->expr instanceof Expr && $this->simpleScalarAnalyzer->isSimpleScalar(
            $onlyStmt->expr
        )) {
            return true;
        }

        if ($this->ifAndAnalyzer->isIfAndWithInstanceof($if->cond)) {
            return true;
        }

        return ! $this->isLastIfOrBeforeLastReturn($nexStmt);
    }

    private function isLastIfOrBeforeLastReturn(?Stmt $nextStmt): bool
    {
        if (! $nextStmt instanceof Stmt) {
            return true;
        }

        return $nextStmt instanceof Return_;
    }
}

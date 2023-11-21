<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Rector\EarlyReturn\Rector\If_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\EarlyReturn\Rector\If_\ChangeOrIfContinueToMultiContinueRector\ChangeOrIfContinueToMultiContinueRectorTest
 */
final class ChangeIfOrReturnToEarlyReturnRector extends AbstractRector
{
    public function __construct(
        private readonly IfManipulator $ifManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changes if || to early return', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function canDrive(Car $car)
    {
        if ($car->hasWheels() || $car->hasFuel()) {
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
        if ($car->hasWheels()) {
            return true;
        }
        if ($car->hasFuel()) {
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
        return [If_::class];
    }

    /**
     * @param If_ $node
     * @return null|If_[]
     */
    public function refactor(Node $node): ?array
    {
        if (! $this->ifManipulator->isIfWithOnly($node, Return_::class)) {
            return null;
        }

        if (! $node->cond instanceof BooleanOr) {
            return null;
        }

        return $this->processMultiIfReturn($node);
    }

    /**
     * @return null|If_[]
     */
    private function processMultiIfReturn(If_ $if): ?array
    {
        $node = clone $if;
        /** @var Return_ $return */
        $return = $if->stmts[0];
        $ifs = $this->createMultipleIfs($if->cond, $return, []);

        // ensure ifs not removed by other rules
        if ($ifs === []) {
            return null;
        }

        $this->mirrorComments($ifs[0], $node);
        return $ifs;
    }

    /**
     * @param If_[] $ifs
     * @return If_[]
     */
    private function createMultipleIfs(Expr $expr, Return_ $return, array $ifs): array
    {
        while ($expr instanceof BooleanOr) {
            $ifs = [...$ifs, ...$this->collectLeftBooleanOrToIfs($expr, $return, $ifs)];
            $ifs[] = new If_($expr->right, [
                'stmts' => [$return],
            ]);

            $expr = $expr->right;
        }

        $lastContinueIf = new If_($expr, [
            'stmts' => [$return],
        ]);

        // the + is on purpose here, to keep only single continue as last
        return $ifs + [$lastContinueIf];
    }

    /**
     * @param If_[] $ifs
     * @return If_[]
     */
    private function collectLeftBooleanOrToIfs(BooleanOr $booleanOr, Return_ $return, array $ifs): array
    {
        $left = $booleanOr->left;
        if (! $left instanceof BooleanOr) {
            $if = new If_($left, [
                'stmts' => [$return],
            ]);

            return [$if];
        }

        return $this->createMultipleIfs($left, $return, $ifs);
    }
}

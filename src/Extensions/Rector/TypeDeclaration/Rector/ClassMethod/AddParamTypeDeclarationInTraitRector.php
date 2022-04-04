<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStanStaticTypeMapper\ValueObject\TypeKind;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Based on AddParamTypeDeclarationRector.
 *
 * Hack to fix bug.
 *
 * DowngradeParameterTypeWideningRector is modifying function `clear` from vendor/symfony/cache/Adapter/AdapterInterface.php:
 * from:
 *     public function clear(string $prefix = '');
 * to:
 *     public function clear($prefix = '');
 * But the same modification is not being done in vendor/symfony/cache/Traits/AbstractAdapterTrait.php
 * So apply this change manually
 *
 * @see https://github.com/leoloso/PoP/issues/597#issue-855005786
 */
final class AddParamTypeDeclarationInTraitRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public final const PARAMETER_TYPEHINTS = 'parameter_typehints';

    /**
     * @var AddParamTypeDeclaration[]
     */
    private array $parameterTypehints = [];

    public function __construct(
        private TypeComparator $typeComparator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $configuration = [
            self::PARAMETER_TYPEHINTS => [new AddParamTypeDeclaration('SomeTrait', 'process', 0, new StringType())],
        ];

        return new RuleDefinition('Add param types where needed', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
trait SomeTrait
{
    public function process($name)
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
trait SomeTrait
{
    public function process(string $name)
    {
    }
}
CODE_SAMPLE
                ,
                $configuration
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var Trait_ */
        $trait = $node->getAttribute(AttributeKey::CLASS_NODE);
        /** @var string $traitName */
        $traitName = $trait->getAttribute(AttributeKey::CLASS_NAME);
        foreach ($this->parameterTypehints as $parameterTypehint) {
            // Check this is the right trait
            if ($traitName !== $parameterTypehint->getObjectType()->getClassName()) {
                continue;
            }

            // Check this is the right method
            if (! $this->isName($node, $parameterTypehint->getMethodName())) {
                continue;
            }

            $this->refactorClassMethodWithTypehintByParameterPosition($node, $parameterTypehint);
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $parameterTypehints = $configuration[self::PARAMETER_TYPEHINTS] ?? [];
        Assert::allIsInstanceOf($parameterTypehints, AddParamTypeDeclaration::class);
        $this->parameterTypehints = $parameterTypehints;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        // skip class methods without args
        if ($classMethod->params === []) {
            return true;
        }

        // only traits
        $classLike = $classMethod->getAttribute(AttributeKey::CLASS_NODE);
        return !($classLike instanceof Trait_);
    }

    private function refactorClassMethodWithTypehintByParameterPosition(
        ClassMethod $classMethod,
        AddParamTypeDeclaration $addParamTypeDeclaration
    ): void {
        $parameter = $classMethod->params[$addParamTypeDeclaration->getPosition()] ?? null;
        if (! $parameter instanceof Param) {
            return;
        }

        $this->refactorParameter($parameter, $addParamTypeDeclaration);
    }

    private function refactorParameter(Param $param, AddParamTypeDeclaration $addParamTypeDeclaration): void
    {
        // already set → no change
        if ($param->type !== null) {
            $currentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
            if ($this->typeComparator->areTypesEqual($currentParamType, $addParamTypeDeclaration->getParamType())) {
                return;
            }
        }

        // remove it
        if ($addParamTypeDeclaration->getParamType() instanceof MixedType) {
            $param->type = null;
            return;
        }

        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            $addParamTypeDeclaration->getParamType(),
            TypeKind::PARAM()
        );

        $param->type = $returnTypeNode;
    }
}

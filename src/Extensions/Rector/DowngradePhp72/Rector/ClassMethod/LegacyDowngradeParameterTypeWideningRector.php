<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Rector\DowngradePhp72\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp72\NodeAnalyzer\NativeTypeClassTreeResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Method `findClassMethod` in NodeRepository was removed on v0.11,
 * hence the original code has been modified somewhat
 *
 * @source https://raw.githubusercontent.com/rectorphp/rector-src/0.10.6/packages/NodeCollector/NodeCollector/NodeRepository.php
 */
final class LegacyDowngradeParameterTypeWideningRector extends AbstractRector
{
    /**
     * @var PhpDocTypeChanger
     */
    private $phpDocTypeChanger;

    /**
     * @var NativeTypeClassTreeResolver
     */
    private $nativeTypeClassTreeResolver;

    /**
     * @var TypeFactory
     */
    private $typeFactory;

    public function __construct(
        PhpDocTypeChanger $phpDocTypeChanger,
        NativeTypeClassTreeResolver $nativeTypeClassTreeResolver,
        TypeFactory $typeFactory,
        private ReflectionProvider $reflectionProvider,
        private AstResolver $reflectionAstResolver,
    ) {
        $this->phpDocTypeChanger = $phpDocTypeChanger;
        $this->nativeTypeClassTreeResolver = $nativeTypeClassTreeResolver;
        $this->typeFactory = $typeFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change param type to match the lowest type in whole family tree',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
interface A
{
    public function test(array $input);
}

class C implements A
{
    public function test($input){}
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
interface A
{
    public function test(array $input);
}

class C implements A
{
    public function test(array $input){}
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isMagic()) {
            return null;
        }

        if ($node->params === []) {
            return null;
        }

        foreach (array_keys($node->params) as $position) {
            $this->refactorParamForSelfAndSiblings($node, (int) $position);
        }

        return null;
    }

    /**
     * The topmost class is the source of truth, so we go only down to avoid up/down collission
     */
    private function refactorParamForSelfAndSiblings(ClassMethod $classMethod, int $position): void
    {
        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            // possibly trait
            return;
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return;
        }

        if (count($classReflection->getAncestors()) === 1) {
            return;
        }

        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);

        // Remove the types in:
        // - all ancestors + their descendant classes
        // - all implemented interfaces + their implementing classes
        $parameterTypesByParentClassLikes = $this->resolveParameterTypesByClassLike(
            $classReflection,
            $methodName,
            $position
        );

        // we need at least 2 methods to have a possible conflict
        if (count($parameterTypesByParentClassLikes) < 2) {
            return;
        }

        $uniqueParameterTypes = $this->typeFactory->uniquateTypes($parameterTypesByParentClassLikes);

        // we need at least 2 unique types
        if (count($uniqueParameterTypes) === 1) {
            return;
        }

        $this->refactorClassWithAncestorsAndChildren($classReflection, $methodName, $position);
    }

    private function removeParamTypeFromMethod(
        ClassLike $classLike,
        int $position,
        string $classMethodName
    ): void {
        $className = $classLike->getAttribute(AttributeKey::CLASS_NAME);
        if ($className === null) {
            return;
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return;
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        if (! $classReflection->hasMethod($classMethodName)) {
            return;
        }

        if ($classReflection->isTrait()) {
            return;
        }
        
        $classMethod = $classLike->getMethod($classMethodName);
        if ($classMethod === null) {
            return;
        }

        $currentClassMethod = $classLike->getMethod($classMethodName);
        if (! $currentClassMethod instanceof ClassMethod) {
            return;
        }

        if (! isset($classMethod->params[$position])) {
            return;
        }

        $param = $classMethod->params[$position];

        // It already has no type => nothing to do
        if ($param->type === null) {
            return;
        }

        // Add the current type in the PHPDoc
        $this->addPHPDocParamTypeToMethod($classMethod, $param);

        // Remove the type
        $param->type = null;
    }

    private function removeParamTypeFromMethodForChildren(
        string $parentClassName,
        string $methodName,
        int $position
    ): void {
        $childrenClassLikes = $this->nodeRepository->findClassesAndInterfacesByType($parentClassName);
        foreach ($childrenClassLikes as $childClassLike) {
            $this->removeParamTypeFromMethod($childClassLike, $position, $methodName);
        }
    }

    /**
     * Add the current param type in the PHPDoc
     */
    private function addPHPDocParamTypeToMethod(ClassMethod $classMethod, Param $param): void
    {
        if ($param->type === null) {
            return;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);

        $paramName = $this->getName($param);
        $mappedCurrentParamType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
        $this->phpDocTypeChanger->changeParamType($phpDocInfo, $mappedCurrentParamType, $param, $paramName);
    }

    /**
     * @return array<class-string, Type>
     */
    private function resolveParameterTypesByClassLike(
        ClassReflection $classReflection,
        string $methodName,
        int $position
    ): array {
        $parameterTypesByParentClassLikes = [];

        foreach ($classReflection->getAncestors() as $ancestorClassReflection) {
            if ($ancestorClassReflection->isTrait()) {
                continue;
            }

            if (! $ancestorClassReflection->hasMethod($methodName)) {
                continue;
            }

            $parameterType = $this->nativeTypeClassTreeResolver->resolveParameterReflectionType(
                $ancestorClassReflection,
                $methodName,
                $position
            );
            $parameterTypesByParentClassLikes[$ancestorClassReflection->getName()] = $parameterType;
        }

        return $parameterTypesByParentClassLikes;
    }

    private function refactorClassWithAncestorsAndChildren(
        ClassReflection $classReflection,
        string $methodName,
        int $position
    ): void {
        foreach ($classReflection->getAncestors() as $ancestorClassRelection) {
            $classLike = $this->nodeRepository->findClassLike($ancestorClassRelection->getName());
            if (! $classLike instanceof ClassLike) {
                continue;
            }

            $currentClassMethod = $classLike->getMethod($methodName);
            if (! $currentClassMethod instanceof ClassMethod) {
                continue;
            }

            $className = $this->getName($classLike);
            if ($className === null) {
                continue;
            }

            /**
             * If it doesn't find the method, it's because the method
             * lives somewhere else.
             * For instance, in test "interface_on_parent_class.php.inc",
             * the ancestorClassReflection abstract class is also retrieved
             * as containing the method, but it does not: it is
             * in its implemented interface. That happens because
             * `ReflectionMethod` doesn't allow to do do the distinction.
             * The interface is also retrieve though, so that method
             * will eventually be refactored.
             */

            $this->removeParamTypeFromMethod($classLike, $position, $methodName);
            $this->removeParamTypeFromMethodForChildren($className, $methodName, $position);
        }
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

use PoP\ComponentModel\AbstractTestCase;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\QueryResolution\DirectiveDataAccessor;
use PoP\ComponentModel\Schema\InputCoercingServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;

class BackedEnumTypeResolverTest extends AbstractTestCase
{
    /**
     * @return class-string<ModuleInterface>
     */
    protected static function getModuleClass(): string
    {
        return Module::class;
    }

    private function createTypeResolver(string $typeResolverClass): AbstractEnumTypeResolver
    {
        /** @var AbstractEnumTypeResolver */
        $typeResolver = new $typeResolverClass();
        $typeResolver->setInstanceManager(InstanceManagerFacade::getInstance());
        return $typeResolver;
    }

    private function createAstNode(string $value): Literal
    {
        return new Literal($value, new Location(1, 1));
    }

    public function testEnumValuesDefaultToTheBackedEnumCases(): void
    {
        $typeResolver = $this->createTypeResolver(TestBackedEnumTypeResolver::class);
        $this->assertSame(['FIRST', 'SECOND'], $typeResolver->getEnumValues());
        $this->assertSame(['FIRST', 'SECOND'], $typeResolver->getConsolidatedEnumValues());
    }

    public function testCoerceValueReturnsTheBackedEnumCase(): void
    {
        $typeResolver = $this->createTypeResolver(TestBackedEnumTypeResolver::class);
        $feedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $coercedValue = $typeResolver->coerceValue('SECOND', $this->createAstNode('SECOND'), $feedbackStore);
        $this->assertSame(TestBackedEnum::SECOND, $coercedValue);
        $this->assertSame(0, $feedbackStore->getErrorCount());
    }

    public function testCoerceInvalidValueProducesAnError(): void
    {
        $typeResolver = $this->createTypeResolver(TestBackedEnumTypeResolver::class);
        $feedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $coercedValue = $typeResolver->coerceValue('THIRD', $this->createAstNode('THIRD'), $feedbackStore);
        $this->assertNull($coercedValue);
        $this->assertSame(1, $feedbackStore->getErrorCount());
    }

    public function testNonBackedEnumKeepsCoercingIntoString(): void
    {
        $typeResolver = $this->createTypeResolver(TestStringEnumTypeResolver::class);
        $feedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $this->assertNull($typeResolver->getBackedEnumClass());
        $this->assertSame('FIRST', $typeResolver->coerceValue('FIRST', $this->createAstNode('FIRST'), $feedbackStore));
        $this->assertFalse($typeResolver->isAlreadyCoercedValue(TestBackedEnum::FIRST));
        $this->assertSame('FIRST', $typeResolver->serialize('FIRST'));
    }

    public function testSerializeReturnsTheValueOfTheCase(): void
    {
        $typeResolver = $this->createTypeResolver(TestBackedEnumTypeResolver::class);
        $this->assertSame('FIRST', $typeResolver->serialize(TestBackedEnum::FIRST));
        $this->assertSame('FIRST', $typeResolver->serialize('FIRST'));
    }

    public function testCaseIsAlreadyCoerced(): void
    {
        $typeResolver = $this->createTypeResolver(TestBackedEnumTypeResolver::class);
        $this->assertTrue($typeResolver->isAlreadyCoercedValue(TestBackedEnum::FIRST));
    }

    public function testDeprecationMessagesForTheCase(): void
    {
        $typeResolver = $this->createTypeResolver(TestBackedEnumTypeResolver::class);
        $this->assertSame([], $typeResolver->getInputValueDeprecationMessages(TestBackedEnum::FIRST));
        $this->assertCount(1, $typeResolver->getInputValueDeprecationMessages(TestBackedEnum::SECOND));
    }

    public function testInputCoercingServiceCoercesListsAndAlreadyCoercedCases(): void
    {
        $typeResolver = $this->createTypeResolver(TestBackedEnumTypeResolver::class);
        /** @var InputCoercingServiceInterface */
        $inputCoercingService = InstanceManagerFacade::getInstance()->getInstance(InputCoercingServiceInterface::class);
        $feedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $coercedValue = $inputCoercingService->coerceInputValue(
            $typeResolver,
            ['FIRST', TestBackedEnum::SECOND],
            'arg',
            false,
            true,
            false,
            $this->createAstNode('FIRST'),
            $feedbackStore,
        );
        $this->assertSame([TestBackedEnum::FIRST, TestBackedEnum::SECOND], $coercedValue);
        $this->assertSame(0, $feedbackStore->getErrorCount());
    }

    public function testDirectiveDataAccessorCoercesPromiseResolvedValues(): void
    {
        $directiveDataAccessor = new DirectiveDataAccessor(
            [
                'single' => 'SECOND',
                'list' => ['FIRST', TestBackedEnum::SECOND],
                'other' => 'SECOND',
                'nullable' => null,
            ],
            [
                'single' => TestBackedEnum::class,
                'list' => TestBackedEnum::class,
                'nullable' => TestBackedEnum::class,
            ],
        );
        $this->assertSame(
            [
                'single' => TestBackedEnum::SECOND,
                'list' => [TestBackedEnum::FIRST, TestBackedEnum::SECOND],
                'other' => 'SECOND',
                'nullable' => null,
            ],
            $directiveDataAccessor->getDirectiveArgs()
        );
    }
}

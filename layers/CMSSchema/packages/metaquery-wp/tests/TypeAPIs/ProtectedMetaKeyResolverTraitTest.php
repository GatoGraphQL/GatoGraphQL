<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaQueryWP\TypeAPIs;

use PHPUnit\Framework\TestCase;

class ProtectedMetaKeyResolverTraitTest extends TestCase
{
    private const ZERO_WIDTH_NON_JOINER = "\u{200C}";
    private const ZERO_WIDTH_SPACE = "\u{200B}";
    private const ZERO_WIDTH_NO_BREAK_SPACE = "\u{FEFF}";
    private const FULLWIDTH_LOW_LINE = "\u{FF3F}";

    /**
     * @param array<string,string[]> $databaseResolvedMetaKeys
     */
    private function getResolver(array $databaseResolvedMetaKeys = []): StubProtectedMetaKeyResolver
    {
        return new StubProtectedMetaKeyResolver($databaseResolvedMetaKeys);
    }

    public function testProtectedKeyIsMatched(): void
    {
        $this->assertTrue($this->getResolver()->isProtected('_secret'));
    }

    public function testNonProtectedKeyIsNotMatched(): void
    {
        $this->assertFalse($this->getResolver()->isProtected('secret'));
    }

    /**
     * @dataProvider provideZeroWeightPrefixedKeys
     */
    public function testKeyResolvedByTheDatabaseToAProtectedKeyIsMatched(string $key): void
    {
        $resolver = $this->getResolver([$key => ['_secret']]);
        $this->assertTrue($resolver->isProtected($key));
    }

    /**
     * @dataProvider provideZeroWeightPrefixedKeys
     */
    public function testKeyResolvedByTheDatabaseToNothingIsNotMatched(string $key): void
    {
        $this->assertFalse($this->getResolver()->isProtected($key));
    }

    /**
     * @return array<string,array{0:string}>
     */
    public static function provideZeroWeightPrefixedKeys(): array
    {
        return [
            'zero-width non-joiner' => [self::ZERO_WIDTH_NON_JOINER . '_secret'],
            'zero-width space' => [self::ZERO_WIDTH_SPACE . '_secret'],
            'zero-width no-break space' => [self::ZERO_WIDTH_NO_BREAK_SPACE . '_secret'],
            'fullwidth low line' => [self::FULLWIDTH_LOW_LINE . 'secret'],
        ];
    }

    public function testKeyResolvedByTheDatabaseToADifferentUnprotectedKeyIsNotMatched(): void
    {
        $key = self::ZERO_WIDTH_NON_JOINER . 'secret';
        $resolver = $this->getResolver([$key => ['secret']]);
        $this->assertFalse($resolver->isProtected($key));
    }

    public function testSurroundingWhitespaceDoesNotHideAProtectedKey(): void
    {
        $resolver = $this->getResolver();
        $this->assertTrue($resolver->isProtected(' _secret'));
        $this->assertTrue($resolver->isProtected('_secret '));
        $this->assertTrue($resolver->isProtected('  _secret  '));
    }

    public function testAnASCIIKeyIsNotResolvedAgainstTheDatabase(): void
    {
        $this->assertSame(['_secret'], $this->getResolver()->getCandidates('_secret'));
    }

    public function testAnASCIIKeyWithWhitespaceOnlyAddsItsTrimmedForm(): void
    {
        $this->assertSame([' _secret ', '_secret'], $this->getResolver()->getCandidates(' _secret '));
    }

    public function testANonASCIIKeyAddsTheKeysTheDatabaseResolvesItTo(): void
    {
        $key = self::ZERO_WIDTH_NON_JOINER . '_secret';
        $resolver = $this->getResolver([$key => ['_secret', '_Secret']]);
        $this->assertSame([$key, '_secret', '_Secret'], $resolver->getCandidates($key));
    }
}

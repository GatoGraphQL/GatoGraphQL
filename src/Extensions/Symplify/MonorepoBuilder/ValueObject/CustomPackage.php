<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject;

use Nette\Utils\Strings;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\SmartFileSystem\SmartFileInfo;

final class CustomPackage
{
    private SmartFileInfo $rootDirectoryFileInfo;
    private string $shortName;
    private string $shortDirectory;

    /**
     * @param array<string,mixed> $json
     */
    public function __construct(
        private array $json,
        private string $name,
        SmartFileInfo $composerJsonFileInfo
    ) {
        $this->shortName = (string) Strings::after($name, '/', -1);

        $this->rootDirectoryFileInfo = new SmartFileInfo($composerJsonFileInfo->getPath());

        $this->shortDirectory = (string) Strings::after($composerJsonFileInfo->getPath(), '/', -1);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasTests(): bool
    {
        $expectedTestsDirectory = $this->rootDirectoryFileInfo->getRealPath() . DIRECTORY_SEPARATOR . 'tests';
        return file_exists($expectedTestsDirectory);
    }

    public function hasSrc(): bool
    {
        $expectedSrcDirectory = $this->rootDirectoryFileInfo->getRealPath() . DIRECTORY_SEPARATOR . 'src';
        return file_exists($expectedSrcDirectory);
    }

    /**
     * A Composer package that loads "johnpbloch/wordpress" in the
     * "require-dev" section is considered a WordPress package
     */
    public function isWordPress(): bool
    {
        return isset($this->json[ComposerJsonSection::REQUIRE_DEV]['johnpbloch/wordpress']);
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getShortDirectory(): string
    {
        return $this->shortDirectory;
    }

    public function getRelativePath(): string
    {
        return $this->rootDirectoryFileInfo->getRelativeFilePathFromCwd();
    }
}

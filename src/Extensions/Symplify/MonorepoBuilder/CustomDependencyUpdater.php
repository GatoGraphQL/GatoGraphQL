<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\SmartFileSystem\SmartFileInfo;

final class CustomDependencyUpdater
{
    public function __construct(private JsonFileManager $jsonFileManager)
    {
    }

    /**
     * @param SmartFileInfo[] $smartFileInfos
     * @param string[] $packageNames
     */
    public function updateFileInfosWithPackagesAndVersion(
        array $smartFileInfos,
        array $packageNames,
        string $version
    ): void {
        foreach ($smartFileInfos as $packageComposerFileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($packageComposerFileInfo);

            $json = $this->processSectionWithPackages($json, $packageNames, $version, ComposerJsonSection::REQUIRE);
            $json = $this->processSectionWithPackages($json, $packageNames, $version, ComposerJsonSection::REQUIRE_DEV);
            $json = $this->processSectionWithPackages($json, $packageNames, $version, ComposerJsonSection::REPLACE);

            $this->jsonFileManager->printJsonToFileInfo($json, $packageComposerFileInfo);
        }
    }

    /**
     * @param string[] $packageReplacements
     * @param SmartFileInfo[] $smartFileInfos
     */
    public function updateFileInfosWithVendorAndVersion(
        array $smartFileInfos,
        array $packageReplacements,
        string $version,
        bool $updateMinimumStabilityIfDev = true
    ): void {
        // If the version is "dev-master" or "dev-main", make sure to add the minimum-stability entry
        $isDevVersion = str_starts_with($version, 'dev-');

        foreach ($smartFileInfos as $packageComposerFileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($packageComposerFileInfo);

            $json = $this->processSection($json, $packageReplacements, $version, ComposerJsonSection::REQUIRE);
            $json = $this->processSection($json, $packageReplacements, $version, ComposerJsonSection::REQUIRE_DEV);
            $json = $this->processSection($json, $packageReplacements, $version, ComposerJsonSection::REPLACE);

            // If the version is "dev-master" or "dev-main", make sure to add the minimum-stability entry
            if ($updateMinimumStabilityIfDev && $isDevVersion) {
                $json[ComposerJsonSection::MINIMUM_STABILITY] = 'dev';
                $json[ComposerJsonSection::PREFER_STABLE] = true;
            }

            $this->jsonFileManager->printJsonToFileInfo($json, $packageComposerFileInfo);
        }
    }

    /**
     * @param mixed[] $json
     * @param string[] $packageNames
     * @return mixed[]
     */
    private function processSectionWithPackages(
        array $json,
        array $packageNames,
        string $targetVersion,
        string $section
    ): array {
        if (! isset($json[$section])) {
            return $json;
        }

        $sectionKeys = array_keys($json[$section]);
        foreach ($sectionKeys as $packageName) {
            if (! in_array($packageName, $packageNames, true)) {
                continue;
            }

            $json[$section][$packageName] = $targetVersion;
        }

        return $json;
    }

    /**
     * @param mixed[] $json
     * @param string[] $packageReplacements
     * @return mixed[]
     */
    private function processSection(array $json, array $packageReplacements, string $targetVersion, string $section): array
    {
        if (! isset($json[$section])) {
            return $json;
        }

        foreach ($json[$section] as $packageName => $packageVersion) {
            if ($this->shouldSkip($packageReplacements, $targetVersion, $packageName, $packageVersion)) {
                continue;
            }

            $json[$section][$packageName] = $targetVersion;
        }

        return $json;
    }

    /**
     * @param string[] $packageReplacements
     */
    private function shouldSkip(
        array $packageReplacements,
        string $targetVersion,
        string $packageName,
        string $packageVersion
    ): bool {
        if (! in_array($packageName, $packageReplacements)) {
            return true;
        }

        return $packageVersion === $targetVersion;
    }
}

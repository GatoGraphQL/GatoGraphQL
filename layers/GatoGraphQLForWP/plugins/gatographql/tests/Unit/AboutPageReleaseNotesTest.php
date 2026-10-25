<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use PHPUnit\Framework\TestCase;

use function array_map;
use function array_slice;
use function array_values;
use function basename;
use function file_get_contents;
use function glob;
use function preg_match;
use function preg_match_all;
use function usort;
use function version_compare;

/**
 * The Release Notes tab of the About page lists the release notes by
 * hand, in docs/general/about/all-locales.md: opening a new version must
 * update it, as the OPEN VERSION directive says.
 */
class AboutPageReleaseNotesTest extends TestCase
{
    private const PLUGIN_DIR = __DIR__ . '/../..';

    private const ABOUT_FILE = self::PLUGIN_DIR . '/docs/general/about/all-locales.md';

    private const RELEASE_NOTES_DIR = self::PLUGIN_DIR . '/docs/release-notes';

    /**
     * The current version, linked from the card at the top of the tab,
     * is the version of the plugin, and the newest of the release notes.
     */
    public function testTheCurrentReleaseIsThePluginsVersion(): void
    {
        $about = (string) file_get_contents(self::ABOUT_FILE);
        if (preg_match('#<span class="gato-about__current-release-number">([0-9.]+)</span>#', $about, $numberMatches) !== 1) {
            $this->fail('The About page shows no current version.');
        }
        if (preg_match('#<a class="button button-primary" href="\.\./\.\./release-notes/([0-9.]+)/en\.md">#', $about, $linkMatches) !== 1) {
            $this->fail('The About page does not link the release notes of the current version.');
        }
        $this->assertSame($numberMatches[1], $linkMatches[1], 'The current version and the release notes it links differ.');

        $plugin = (string) file_get_contents(self::PLUGIN_DIR . '/gatographql.php');
        if (preg_match('#^Version:\s*(\d+\.\d+)\.#m', $plugin, $versionMatches) !== 1) {
            $this->fail('The plugin declares no version.');
        }
        $this->assertSame($versionMatches[1], $numberMatches[1], 'The About page does not show the version of the plugin as the current one.');

        $releaseNotesVersions = $this->getReleaseNotesVersions();
        $this->assertSame($releaseNotesVersions[0], $numberMatches[1], 'The current version is not the newest of the release notes.');
    }

    /**
     * Every other version is listed once, newest first, under the group
     * of its major version.
     */
    public function testEveryOtherReleaseIsListedUnderItsGroup(): void
    {
        $about = (string) file_get_contents(self::ABOUT_FILE);
        preg_match_all(
            '#<span class="gato-about__version-group-label">(\d+)\.x(?: – (\d+)\.x)?</span>\s*<div class="gato-about__version-list">(.*?)</div>#s',
            $about,
            $groups,
            PREG_SET_ORDER
        );
        $this->assertNotEmpty($groups, 'The About page lists no versions.');

        $listedVersions = [];
        foreach ($groups as $group) {
            $fromMajor = (int) $group[1];
            $toMajor = $group[2] !== '' ? (int) $group[2] : $fromMajor;
            preg_match_all('#<a href="\.\./\.\./release-notes/([0-9.]+)/en\.md">([0-9.]+)</a>#', $group[3], $links, PREG_SET_ORDER);
            foreach ($links as $link) {
                $this->assertSame($link[1], $link[2], 'Version ' . $link[2] . ' links the release notes of another version.');
                $major = (int) $link[1];
                $this->assertTrue(
                    $major >= $fromMajor && $major <= $toMajor,
                    'Version ' . $link[1] . ' is listed under the wrong group.'
                );
                $listedVersions[] = $link[1];
            }
        }

        $releaseNotesVersions = $this->getReleaseNotesVersions();
        $this->assertSame(
            array_values(array_slice($releaseNotesVersions, 1)),
            $listedVersions,
            'The About page does not list the release notes of every version but the current one, newest first.'
        );
    }

    /**
     * @return string[] The versions with release notes, newest first
     */
    private function getReleaseNotesVersions(): array
    {
        $versions = array_map(
            static fn (string $dir): string => basename($dir),
            glob(self::RELEASE_NOTES_DIR . '/*', GLOB_ONLYDIR) ?: []
        );
        usort($versions, static fn (string $a, string $b): int => version_compare($b, $a));
        return $versions;
    }
}

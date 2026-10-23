<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function array_column;
use function array_map;
use function count;
use function str_repeat;
use function str_replace;

class SettingsControlHelpersTest extends TestCase
{
    /**
     * @param array<string,mixed> $itemSetting
     */
    #[DataProvider('provideControl')]
    public function testControl(array $itemSetting, string $expectedControl): void
    {
        $this->assertSame($expectedControl, SettingsControlHelpers::getControl($itemSetting));
    }

    /**
     * @return array<string,array{0:array<string,mixed>,1:string}>
     */
    public static function provideControl(): array
    {
        return [
            'bool is a switch' => [
                [Properties::TYPE => Properties::TYPE_BOOL],
                Properties::CONTROL_SWITCH,
            ],
            'few short options are segmented' => [
                [Properties::TYPE => Properties::TYPE_STRING, Properties::POSSIBLE_VALUES => ['a' => 'Sequential', 'b' => 'Parallel']],
                Properties::CONTROL_SEGMENTED,
            ],
            'one option only stays a dropdown' => [
                [Properties::TYPE => Properties::TYPE_STRING, Properties::POSSIBLE_VALUES => ['a' => 'Only']],
                Properties::CONTROL_SELECT,
            ],
            '5 short options are segmented' => [
                [Properties::TYPE => Properties::TYPE_STRING, Properties::POSSIBLE_VALUES => ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E']],
                Properties::CONTROL_SEGMENTED,
            ],
            'more than 5 options are a dropdown' => [
                [Properties::TYPE => Properties::TYPE_STRING, Properties::POSSIBLE_VALUES => ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E', 'f' => 'F']],
                Properties::CONTROL_SELECT,
            ],
            'long labels are a dropdown' => [
                [Properties::TYPE => Properties::TYPE_STRING, Properties::POSSIBLE_VALUES => ['a' => 'The entries must already be there', 'b' => 'Create them']],
                Properties::CONTROL_SELECT,
            ],
            'values appended at runtime are a dropdown' => [
                [Properties::TYPE => Properties::TYPE_STRING, Properties::POSSIBLE_VALUES => ['a' => 'A', 'b' => 'B'], Properties::APPEND_VALUE_IF_NON_EXISTING => true],
                Properties::CONTROL_SELECT,
            ],
            'multiple values are a checklist' => [
                [Properties::TYPE => Properties::TYPE_ARRAY, Properties::POSSIBLE_VALUES => ['a' => 'A', 'b' => 'B'], Properties::IS_MULTIPLE => true],
                Properties::CONTROL_CHECKLIST,
            ],
            'array without options is a tag input' => [
                [Properties::TYPE => Properties::TYPE_ARRAY],
                Properties::CONTROL_TAGS,
            ],
            'property array with options is a grid' => [
                [Properties::TYPE => Properties::TYPE_PROPERTY_ARRAY, Properties::KEY_LABELS => ['en' => 'English'], Properties::POSSIBLE_VALUES => ['a' => 'A']],
                Properties::CONTROL_GRID,
            ],
            'property array of inputs is a grid' => [
                [Properties::TYPE => Properties::TYPE_PROPERTY_ARRAY, Properties::KEY_LABELS => ['en' => 'English']],
                Properties::CONTROL_GRID,
            ],
            'property array of bools are chips' => [
                [Properties::TYPE => Properties::TYPE_PROPERTY_ARRAY, Properties::KEY_LABELS => ['en' => 'English'], Properties::SUBTYPE => Properties::TYPE_BOOL],
                Properties::CONTROL_CHIPS,
            ],
            'string is an input' => [
                [Properties::TYPE => Properties::TYPE_STRING],
                Properties::CONTROL_INPUT,
            ],
            'int is an input' => [
                [Properties::TYPE => Properties::TYPE_INT],
                Properties::CONTROL_INPUT,
            ],
            'string marked as textarea' => [
                [Properties::TYPE => Properties::TYPE_STRING, Properties::USE_TEXTAREA => true],
                Properties::CONTROL_TEXTAREA,
            ],
            'forced control wins' => [
                [Properties::TYPE => Properties::TYPE_STRING, Properties::POSSIBLE_VALUES => ['a' => 'A', 'b' => 'B'], Properties::CONTROL => Properties::CONTROL_SELECT],
                Properties::CONTROL_SELECT,
            ],
            'null forced as a group is a group' => [
                [Properties::TYPE => Properties::TYPE_NULL, Properties::TITLE => 'Slugs', Properties::CONTROL => Properties::CONTROL_GROUP],
                Properties::CONTROL_GROUP,
            ],
            'null with underlined title is a group' => [
                [Properties::TYPE => Properties::TYPE_NULL, Properties::TITLE => '<u>Custom posts:</u>'],
                Properties::CONTROL_GROUP,
            ],
            'null with only a rule is a divider' => [
                [Properties::TYPE => Properties::TYPE_NULL, Properties::DESCRIPTION => '<hr/>'],
                Properties::CONTROL_DIVIDER,
            ],
            'null with content is content' => [
                [Properties::TYPE => Properties::TYPE_NULL, Properties::DESCRIPTION => '<button>Activate</button>'],
                Properties::CONTROL_CONTENT,
            ],
        ];
    }

    public function testGridEntryControl(): void
    {
        $this->assertSame(
            Properties::CONTROL_SEGMENTED,
            SettingsControlHelpers::getGridEntryControl([Properties::POSSIBLE_VALUES => ['d' => 'Default', 'e' => 'Enabled', 'x' => 'Disabled']])
        );
        $this->assertSame(
            Properties::CONTROL_SELECT,
            SettingsControlHelpers::getGridEntryControl([Properties::POSSIBLE_VALUES => ['d' => 'Default', 'e' => 'Enabled', 'x' => 'Disabled', 'n' => 'None']])
        );
        $this->assertSame(
            Properties::CONTROL_INPUT,
            SettingsControlHelpers::getGridEntryControl([])
        );
        $this->assertSame(
            Properties::CONTROL_SELECT,
            SettingsControlHelpers::getGridEntryControl([Properties::POSSIBLE_VALUES => ['d' => 'Default', 'e' => 'Enabled'], Properties::ENTRY_CONTROL => Properties::CONTROL_SELECT])
        );
    }

    public function testGroupsSplitOnHeadingsAndDividers(): void
    {
        $setting = fn (string $name) => [Properties::NAME => $name, Properties::TYPE => Properties::TYPE_BOOL];
        $groups = SettingsControlHelpers::getGroups([
            $setting('a'),
            [Properties::NAME => 'divider-0', Properties::TYPE => Properties::TYPE_NULL, Properties::DESCRIPTION => '<hr/>'],
            [Properties::NAME => 'divider-1', Properties::TYPE => Properties::TYPE_NULL, Properties::DESCRIPTION => '<hr/>'],
            $setting('b'),
            [Properties::NAME => 'heading-1', Properties::TYPE => Properties::TYPE_NULL, Properties::TITLE => '<u>Custom posts:</u>'],
            $setting('c'),
            $setting('d'),
            [Properties::NAME => 'heading-2', Properties::TYPE => Properties::TYPE_NULL, Properties::TITLE => 'Empty', Properties::CONTROL => Properties::CONTROL_GROUP],
            [Properties::NAME => 'heading-3', Properties::TYPE => Properties::TYPE_NULL, Properties::TITLE => 'Taxonomies', Properties::CONTROL => Properties::CONTROL_GROUP],
            $setting('e'),
        ]);

        $this->assertSame(
            [null, null, 'heading-1', 'heading-3'],
            array_map(fn (array $group) => $group['heading'][Properties::NAME] ?? null, $groups)
        );
        $this->assertSame(
            [['a'], ['b'], ['c', 'd'], ['e']],
            array_map(fn (array $group) => array_column($group['settings'], Properties::NAME), $groups)
        );
    }

    public function testHeadingWithContentIsKeptWithoutSettings(): void
    {
        $groups = SettingsControlHelpers::getGroups([
            [Properties::NAME => 'heading', Properties::TYPE => Properties::TYPE_NULL, Properties::TITLE => 'Note', Properties::CONTROL => Properties::CONTROL_GROUP, Properties::DESCRIPTION => 'Some text'],
        ]);
        $this->assertSame(1, count($groups));
    }

    public function testSectionHelpGoesBesideTheTitleAndNotInACard(): void
    {
        $sectionHelp = [Properties::NAME => 'section-help', Properties::TYPE => Properties::TYPE_NULL, Properties::CONTROL => Properties::CONTROL_SECTION_HELP, Properties::HELP => '<p>What it does</p>'];
        $setting = [Properties::NAME => 'a', Properties::TYPE => Properties::TYPE_BOOL];
        $this->assertSame('<p>What it does</p>', SettingsControlHelpers::getSectionHelp([$sectionHelp, $setting]));
        $this->assertSame('', SettingsControlHelpers::getSectionHelp([$setting]));
        $this->assertSame(
            [['heading' => null, 'settings' => [$setting]]],
            SettingsControlHelpers::getGroups([$sectionHelp, $setting])
        );
    }

    public function testJSONIsIndented(): void
    {
        $this->assertSame("{\n    \"reasoning_effort\": \"none\",\n    \"kwargs\": {\n        \"url\": \"https://a.b/c\"\n    }\n}", SettingsControlHelpers::formatJSON('{"reasoning_effort":"none","kwargs":{"url":"https://a.b/c"}}'));
        $this->assertSame('{}', SettingsControlHelpers::formatJSON('{}'));
        $this->assertSame('', SettingsControlHelpers::formatJSON(''));
        $this->assertSame('{"a":', SettingsControlHelpers::formatJSON('{"a":'), 'A value that is not valid JSON must stay as it is.');
        $this->assertSame(3, SettingsControlHelpers::getJSONTextareaRows('{}'));
        $this->assertSame(7, SettingsControlHelpers::getJSONTextareaRows("{\n\n\n\n\n}"));
        $this->assertSame(16, SettingsControlHelpers::getJSONTextareaRows(str_repeat("\n", 40)));
    }

    public function testFullWidthInputIsAskedFor(): void
    {
        $this->assertTrue(SettingsControlHelpers::isFullWidthInput([Properties::TYPE => Properties::TYPE_STRING, Properties::FULL_WIDTH => true]));
        $this->assertFalse(SettingsControlHelpers::isFullWidthInput([Properties::TYPE => Properties::TYPE_STRING]));
        $this->assertFalse(SettingsControlHelpers::isFullWidthInput([Properties::TYPE => Properties::TYPE_STRING, Properties::FULL_WIDTH => false]));
        $this->assertFalse(SettingsControlHelpers::isFullWidthInput([Properties::TYPE => Properties::TYPE_STRING, Properties::USE_TEXTAREA => true, Properties::FULL_WIDTH => true]));
        $this->assertFalse(SettingsControlHelpers::isFullWidthInput([Properties::TYPE => Properties::TYPE_STRING, Properties::POSSIBLE_VALUES => ['a' => 'A', 'b' => 'B'], Properties::FULL_WIDTH => true]));
    }

    public function testDescriptionHelpMovesBehindTheButton(): void
    {
        $collapsible = '<details class="gato-collapsible"><summary>Show details</summary><div class="gato-collapsible-content">More <div>about</div> it</div></details>';
        $this->assertSame(
            ['description' => 'One line.', 'help' => 'More <div>about</div> it'],
            SettingsControlHelpers::splitDescriptionHelp('One line. ' . $collapsible)
        );
        $this->assertSame(
            ['description' => 'One line.', 'help' => 'A<br/><br/>B'],
            SettingsControlHelpers::splitDescriptionHelp('One line.<br/>' . str_replace('More <div>about</div> it', 'A', $collapsible) . str_replace('More <div>about</div> it', 'B', $collapsible))
        );
        $this->assertSame(
            ['description' => 'One line.', 'help' => 'Starts here'],
            SettingsControlHelpers::splitDescriptionHelp('One line. ' . str_replace('More <div>about</div> it', '<br/> <br>Starts here', $collapsible))
        );
        $this->assertSame(
            ['description' => 'Only a line', 'help' => ''],
            SettingsControlHelpers::splitDescriptionHelp('Only a line')
        );
    }

    public function testSelectedValueComparesAsStrings(): void
    {
        $this->assertTrue(SettingsControlHelpers::isSelectedValue(1, ['1']));
        $this->assertTrue(SettingsControlHelpers::isSelectedValue('1', [1]));
        $this->assertTrue(SettingsControlHelpers::isSelectedValue('post', ['page', 'post']));
        $this->assertFalse(SettingsControlHelpers::isSelectedValue('0', ['']));
        $this->assertFalse(SettingsControlHelpers::isSelectedValue('post', ['page']));
    }

    public function testNumberOutOfRange(): void
    {
        $this->assertFalse(SettingsControlHelpers::isNumberOutOfRange(5, null, null));
        $this->assertFalse(SettingsControlHelpers::isNumberOutOfRange(5, 1, 10));
        $this->assertFalse(SettingsControlHelpers::isNumberOutOfRange(10, 1, 10));
        $this->assertTrue(SettingsControlHelpers::isNumberOutOfRange(0, 1, null));
        $this->assertTrue(SettingsControlHelpers::isNumberOutOfRange(11, null, 10));
    }

    public function testCountLeafValues(): void
    {
        $this->assertSame(0, SettingsControlHelpers::countLeafValues([]));
        $this->assertSame(
            6,
            SettingsControlHelpers::countLeafValues([
                'action' => 'save',
                'option' => [
                    'a' => '1',
                    'b' => ['x', 'y'],
                    'c' => ['es' => 'default', 'fr' => []],
                ],
                'empty' => '',
            ])
        );
    }

    public function testTitleWithoutUnit(): void
    {
        $this->assertSame('Request timeout', SettingsControlHelpers::getTitleWithoutUnit('Request timeout (seconds)', 'seconds'));
        $this->assertSame('Size limit', SettingsControlHelpers::getTitleWithoutUnit('Size limit (MB)', 'MB'));
        $this->assertSame('Time per request', SettingsControlHelpers::getTitleWithoutUnit('Time per request (Seconds)', 'seconds'));
        $this->assertSame('Request timeout (seconds)', SettingsControlHelpers::getTitleWithoutUnit('Request timeout (seconds)', null));
        $this->assertSame('Max length (characters)', SettingsControlHelpers::getTitleWithoutUnit('Max length (characters)', 'tokens'));
        $this->assertSame('Max length', SettingsControlHelpers::getTitleWithoutUnit('Max length', 'characters'));
    }

    public function testTheSameJSONIndentedIsTheSame(): void
    {
        $this->assertTrue(SettingsControlHelpers::isSameJSON('{"reasoning_effort":"none"}', "{\r\n    \"reasoning_effort\": \"none\"\r\n}"));
        $this->assertFalse(SettingsControlHelpers::isSameJSON('{"reasoning_effort":"none"}', '{"reasoning_effort":"low"}'));
        $this->assertFalse(SettingsControlHelpers::isSameJSON('{"a":1,"b":2}', '{"b":2,"a":1}'));
        $this->assertFalse(SettingsControlHelpers::isSameJSON('{oops', '{ oops'));
        $this->assertTrue(SettingsControlHelpers::isSameJSON('{oops', '{oops'));
        $this->assertFalse(SettingsControlHelpers::isSameJSON('', 'null'));
    }

    public function testIndentingJSONNeverChangesItsValue(): void
    {
        $this->assertSame("{\n    \"temperature\": 1.0\n}", SettingsControlHelpers::formatJSON('{"temperature":1.0}'));
        $this->assertSame('{"id":12345678901234567890}', SettingsControlHelpers::formatJSON('{"id":12345678901234567890}'));
        $this->assertSame('{oops', SettingsControlHelpers::formatJSON('{oops'));
        $this->assertSame("{\n    \"a\": [\n        1,\n        \"b\"\n    ]\n}", SettingsControlHelpers::formatJSON('{"a":[1,"b"]}'));
        $this->assertFalse(SettingsControlHelpers::isSameJSON('{"temperature":1.0}', '{"temperature":1}'));
        $this->assertFalse(SettingsControlHelpers::isSameJSON('{"id":12345678901234567890}', '{"id":12345678901234567891}'));
        $this->assertSame('{"name":"\\u00e9"}', SettingsControlHelpers::formatJSON('{"name":"\\u00e9"}'));
        $this->assertTrue(SettingsControlHelpers::isSameJSON('{"a": "x y", "b": []}', "{\n    \"a\": \"x y\",\n    \"b\": []\n}"));
        $this->assertFalse(SettingsControlHelpers::isSameJSON('{"a":"x y"}', '{"a":"xy"}'));
    }
}

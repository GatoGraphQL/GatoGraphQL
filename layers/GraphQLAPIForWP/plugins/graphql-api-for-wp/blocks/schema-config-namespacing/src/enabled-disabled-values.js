/**
 * This value is not defined as GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_DEFAULT,
 * because the default value is not saved in the entry (it's just null)
 * But it is defined here to keep this value DRY,
 * when declaring the state's default value in index.js
 */
const ATTRIBUTE_VALUE_DEFAULT = 'default';
/**
 * GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_ENABLED
 */
const ATTRIBUTE_VALUE_ENABLED = 'enabled';
/**
 * GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_DISABLED
 */
const ATTRIBUTE_VALUE_DISABLED = 'disabled';

export {
	ATTRIBUTE_VALUE_DEFAULT,
	ATTRIBUTE_VALUE_ENABLED,
	ATTRIBUTE_VALUE_DISABLED,
};

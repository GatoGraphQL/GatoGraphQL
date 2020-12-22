/**
 * This value is not defined as GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT,
 * because the default value is not saved in the entry (it's just null)
 * But it is defined here to keep this value DRY,
 * when declaring the state's default value in index.js
 */
const ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT = 'default';
/**
 * GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED
 */
const ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED = 'enabled';
/**
 * GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED
 */
const ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED = 'disabled';

export {
	ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT,
	ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED,
	ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED,
};

/**
 * This value is not defined as GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues::DEFAULT,
 * because the default value is not saved in the entry (it's just null)
 * But it is defined here to keep this value DRY,
 * when declaring the state's default value in index.js
 */
const ATTRIBUTE_VALUE_DEFAULT = 'default';
/**
 * GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues::ENABLED
 */
const ATTRIBUTE_VALUE_ENABLED = 'enabled';
/**
 * GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues::DISABLED
 */
const ATTRIBUTE_VALUE_DISABLED = 'disabled';

export {
	ATTRIBUTE_VALUE_DEFAULT,
	ATTRIBUTE_VALUE_ENABLED,
	ATTRIBUTE_VALUE_DISABLED,
};

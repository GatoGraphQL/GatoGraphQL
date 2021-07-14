/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */

/**
 * Application imports
 */
import SchemaConfigMutationSchemeCard from './schema-config-mutation-scheme-card';

const isPublicPrivateSchemaEnabled = window.graphqlApiSchemaConfigOptions ? window.graphqlApiSchemaConfigOptions.isPublicPrivateSchemaEnabled : true;
const isAdminSchemaEnabled = window.graphqlApiSchemaConfigOptions ? window.graphqlApiSchemaConfigOptions.isAdminSchemaEnabled : true;
const isSchemaNamespacingEnabled = window.graphqlApiSchemaConfigOptions ? window.graphqlApiSchemaConfigOptions.isSchemaNamespacingEnabled : true;
const isNestedMutationsEnabled = window.graphqlApiSchemaConfigOptions ? window.graphqlApiSchemaConfigOptions.isNestedMutationsEnabled : true;

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigMutationSchemeCard
				isPublicPrivateSchemaEnabled={ isPublicPrivateSchemaEnabled }
				isAdminSchemaEnabled={ isAdminSchemaEnabled }
				isSchemaNamespacingEnabled={ isSchemaNamespacingEnabled }
				isNestedMutationsEnabled={ isNestedMutationsEnabled }
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import { MarkdownInfoModalButton } from '@graphqlapi/components';
import { getImplicitFeaturesDocMarkdownContentOrUseDefault } from '../implicit-features-doc-markdown-loader';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'schema-configuration-document-settings-panel';
/**
 * Component
 */
const implicitFeaturesDocEntries = [
    [
        'AnyBuiltInScalar type',
        'any-built-in-scalar'
    ],
    [
        'OneOf Input Object',
        'oneof-input-object'
    ],
    [
        'Query Schema Extensions via Introspection',
        'query-schema-extensions-via-introspection'
    ],
];
const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Additional Documentation', 'graphql-api') }
    >
        <p>{ __('Browse documentation for implicit features in the GraphQL API:', 'graphql-api') }</p>
        <p>
            {
                implicitFeaturesDocEntries.map( ( entry ) =>
                    <MarkdownInfoModalButton
                        text={ entry[0] }
                        title={ __(`Documentation for: "${ entry[0] }"`, 'graphql-api') }
                        pageFilename={ entry[1] }
                        getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
                        isSmall={ false }
                        className="graphql-api-info-modal-button text-wrap"
                    />
                )
            }
        </p>
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };

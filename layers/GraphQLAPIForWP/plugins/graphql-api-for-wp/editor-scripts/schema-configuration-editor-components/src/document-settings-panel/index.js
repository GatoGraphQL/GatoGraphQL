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
        'AnyBuiltInScalar',
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
        {
            implicitFeaturesDocEntries.map( ( entry ) =>
                <MarkdownInfoModalButton
                    label={ entry[0] }
                    title={ __(`Documentation for: "${ entry[0] }"`, 'graphql-api') }
                    pageFilename={ entry[1] }
                    getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
                    isSmall={ false }
                />
            )
        }
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };

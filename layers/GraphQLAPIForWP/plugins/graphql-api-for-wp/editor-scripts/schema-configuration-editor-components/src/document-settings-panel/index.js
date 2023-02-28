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
const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Additional Documentation', 'graphql-api') }
    >
        <MarkdownInfoModalButton
            title={ __('AnyBuiltInScalar', 'graphql-api') }
            pageFilename="any-built-in-scalar"
            getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
        />
        <MarkdownInfoModalButton
            title={ __('OneOf Input Object', 'graphql-api') }
            pageFilename="oneof-input-object"
            getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
        />
        <MarkdownInfoModalButton
            title={ __('Query Schema Extensions via Introspection', 'graphql-api') }
            pageFilename="query-schema-extensions-via-introspection"
            getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
        />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };

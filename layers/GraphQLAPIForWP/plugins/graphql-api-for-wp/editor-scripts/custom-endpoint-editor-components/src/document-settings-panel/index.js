/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import { MarkdownInfoModalButton } from '@graphqlapi/components';
import { getMarkdownContentOrUseDefault } from '../markdown-loader';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'custom-endpoint-document-settings-panel';
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
            getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
        />
        <MarkdownInfoModalButton
            title={ __('OneOf Input Object', 'graphql-api') }
            pageFilename="oneof-input-object"
            getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
        />
        <MarkdownInfoModalButton
            title={ __('Query Schema Extensions via Introspection', 'graphql-api') }
            pageFilename="query-schema-extensions-via-introspection"
            getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
        />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import PersistedQueryEndpointProperties from './persisted-query-endpoint-properties.js';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'persisted-query-endpoint-properties-panel';

const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Persisted Query Endpoint Properties', 'graphql-api') }
    >
        <PersistedQueryEndpointProperties />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };

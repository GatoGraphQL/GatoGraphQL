/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import PersistedQueryEndpointOverview from './persisted-query-endpoint-overview.js';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'persisted-query-endpoint-overview-panel';

const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Persisted Query Endpoint Overview', 'graphql-api') }
    >
        <PersistedQueryEndpointOverview />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };

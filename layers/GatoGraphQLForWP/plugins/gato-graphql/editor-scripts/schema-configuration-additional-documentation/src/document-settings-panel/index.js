/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import {
    MarkdownInfoModalButton,
} from '@gatographql/components';
import { getImplicitFeaturesDocMarkdownContentOrUseDefault } from '../implicit-features-doc-markdown-loader';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'schema-configuration-additional-documentation-panel';
/**
 * Component
 */
const implicitFeaturesDocEntries = [
    [
        'AnyBuiltInScalar Type',
        'any-built-in-scalar'
    ],
    [
        'Custom Scalars Pack',
        'custom-scalars',
    ],
    [
        'Dynamic Variables',
        'dynamic-variables',
    ],
    [
        'DangerouslyNonSpecificScalar Type',
        'dangerously-non-specific-scalar'
    ],
    [
        'OneOf Input Object',
        'oneof-input-object'
    ],
    [
        'Query Schema Extensions via Introspection',
        'query-schema-extensions-via-introspection'
    ],
    [
        'Restrict Field Directives to Specific Types',
        'restrict-field-directives-to-specific-types'
    ],
];
const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Additional Gato GraphQL Documentation', 'gato-graphql') }
    >
        <p>{ __('Docs for additional features in the Gato GraphQL:', 'gato-graphql') }</p>
        <p>
            {
                implicitFeaturesDocEntries.map( ( entry ) =>
                    <div>
                            <MarkdownInfoModalButton
                            text={ entry[0] }
                            title={ __(`Documentation for: "${ entry[0] }"`, 'gato-graphql') }
                            pageFilename={ entry[1] }
                            getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
                            isSmall={ false }
                            className="gato-graphql-info-modal-button text-wrap"
                        />
                    </div>
                )
            }
        </p>
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };

/**
 * Application imports
 */
import SchemaConfigMutationSchemeCard from './schema-config-mutation-scheme-card';
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigMutationSchemeCard
				{ ...props }
				getMarkdownContentCallback={ getModuleDocMarkdownContentOrUseDefault }
			/>
		</div>
	)
}

export default EditBlock;

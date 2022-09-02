/**
 * Application imports
 */
import SchemaConfigNamespacingCard from './schema-config-namespacing-card';
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigNamespacingCard
				{ ...props }
				getMarkdownContentCallback={ getModuleDocMarkdownContentOrUseDefault }
			/>
		</div>
	)
}

export default EditBlock;

import { __ } from '@wordpress/i18n';
import SchemaModeControlCard from './schema-mode-control-card';

const SchemaMode = ( props ) => {
	const { className } = props;
	return (
		<>
			<div className={ className+'__item_data_schema_mode' }>
				<div className={ className+'__item_data__schema_mode' }>
					<SchemaModeControlCard
						{ ...props }
					/>
				</div>
			</div>
			<div className={ className+'__item_data__title' }>
				<strong>{ __('Who can access:', 'graphql-api') }</strong>
			</div>
		</>
	);
}

export default SchemaMode;

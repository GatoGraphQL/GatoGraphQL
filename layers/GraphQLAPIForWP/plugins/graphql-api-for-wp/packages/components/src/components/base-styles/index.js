export const getEditableOnFocusComponentClass = ( isSelected ) => {
	return `nested-component editable-on-focus is-selected-${ isSelected }`;
}

export const getCustomizableConfigurationComponentClass = ( isApplied ) => {
	return `customizable-configuration is-applied-${ isApplied }`;
}

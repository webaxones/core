import { TextControl } from '@wordpress/components'

export const Text = ( { fieldValue, field, onChange } ) => {

	return (
		<TextControl key={ field.id }
			help={ field.hasOwnProperty('help') ? field.help : '' }
			label={ field.label }
			value={ fieldValue || '' }
			onChange={ ( value ) => {
				onChange( value, field.id )
			} }
		/>
	)
}
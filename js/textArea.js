import { TextareaControl } from '@wordpress/components'

export const TextArea = ( { fieldValue, field, onChange } ) => {

	return (
		<TextareaControl key={ field.id }
			help={ field.hasOwnProperty('help') ? field.help : '' }
			label={ field.label }
			type={ 'number' }
			value={ fieldValue || '' }
			onChange={ ( value ) => {
				onChange( value, field.id )
			} }
		/>
	)
}
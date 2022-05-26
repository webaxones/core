import { CheckboxControl } from '@wordpress/components'

export const Checkbox = ( { fieldValue, field, onChange } ) => {
	return (
		<div className='wax-components-field'>
			<CheckboxControl key={ field.id }
				label={ field.label }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				checked={ fieldValue || false }
				onChange={ ( value ) => {
					onChange( value, field.id )
				} }
			/>
		</div>
	)
}

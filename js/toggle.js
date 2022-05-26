import { ToggleControl } from '@wordpress/components'

export const Toggle = ( { fieldValue, field, onChange } ) => {
	return (
		<div className='wax-components-field'>
			<ToggleControl key={ field.id }
				label={ field.label }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				checked={ fieldValue || false }
				onChange={ ( checked ) => {
					onChange( checked, field.id )
				} }
			/>
		</div>
	)
}

import { ToggleControl } from '@wordpress/components'

export const Toggle = ( { fieldValue, field, onChange } ) => {
	console.log(fieldValue)
	return (
		<ToggleControl key={ field.id }
            label={ field.label }
            help={ field.hasOwnProperty('help') ? field.help : '' }
            checked={ fieldValue || false }
            onChange={ ( checked ) => {
				onChange( checked, field.id )
			} }
        />
	)
}

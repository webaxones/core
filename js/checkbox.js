import { CheckboxControl } from '@wordpress/components'

export const Checkbox = ( { fieldValue, field, onChange } ) => {
	return (
		<CheckboxControl key={ field.id }
            label={ field.label }
            help={ field.hasOwnProperty('help') ? field.help : '' }
            checked={ fieldValue || false }
            onChange={ ( value ) => {
				onChange( value, field.id )
			} }
        />
	)
}

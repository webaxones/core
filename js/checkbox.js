import { CheckboxControl } from '@wordpress/components'
import { MainContext } from './mainContext'

export const Checkbox = ( { field } ) => {
	const mainState = React.useContext( MainContext )
	return (
		<div className='wax-components-field'>
			<CheckboxControl key={ field.id }
				label={ field.label }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				checked={ field.value || false }
				onChange={ ( value ) => {
					mainState.onChange( value, field.id )
				} }
			/>
		</div>
	)
}

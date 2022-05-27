import { ToggleControl } from '@wordpress/components'
import { MainContext } from './mainContext'

export const Toggle = ( { field, onChange } ) => {
	const mainState = React.useContext( MainContext )
	return (
		<div className='wax-components-field'>
			<ToggleControl key={ field.id }
				label={ field.label }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				checked={ field.value || false }
				onChange={ ( checked ) => {
					mainState.onChange( checked, field.id )
				} }
			/>
		</div>
	)
}

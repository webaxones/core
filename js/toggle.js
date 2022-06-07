import { ToggleControl } from '@wordpress/components'
import { MainContext } from './app/context'
import { NewLine } from './components/newLine'

export const Toggle = ( { field, condition } ) => {
	const mainState = React.useContext( MainContext )
	return (
		<>
		<NewLine field={ field } condition={ condition } />
		<div className='webaxones-field'>
			<ToggleControl key={ field.slug }
			 	className='webaxones-field__toggle'
				label={ field.label }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				checked={ field.value || false }
				onChange={ ( checked ) => {
					mainState.onChange( checked, field.slug )
				} }
			/>
		</div>
		</>
	)
}

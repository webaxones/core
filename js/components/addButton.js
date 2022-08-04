import { __ } from '@wordpress/i18n'
import { MainContext, RepeaterContext } from '../app/context'
import { Button } from '@wordpress/components'

export const AddButton = ( { fieldRepeater } ) => {
	const mainState = React.useContext( MainContext )
	const repeaterState = React.useContext( RepeaterContext )
	return(
		<Button
			isSmall
			className='button webaxones-field__repeater__add-row'
			onClick={ () => {
				repeaterState.onAddRow( fieldRepeater.slug, mainState )
				mainState.onChange( mainState.fields.find( x => x.slug === fieldRepeater.slug ).value, fieldRepeater.slug )
			} }
		>
			{ __( 'Add Row', 'webaxones-core' ) }
		</Button>
	)
}
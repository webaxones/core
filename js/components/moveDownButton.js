import { __ } from '@wordpress/i18n'
import { MainContext, RepeaterContext } from '../app/context'
import { Button } from '@wordpress/components'

export const MoveDownButton = ( { fieldRepeaterSlug, fieldSlug } ) => {
	const mainState = React.useContext( MainContext )
	const repeaterState = React.useContext( RepeaterContext )

	return(
		<Button
			isSmall
			className='button webaxones-field__repeater__btn--round webaxones-field__repeater__move-down'
			icon='arrow-down'
			label={ __( 'Move to top', 'webaxones-core' ) }
			onClick={ () => {
				repeaterState.onMoveRow( fieldRepeaterSlug, fieldSlug, mainState, 'down' )
				const repeater = mainState.fields.find( x => x.slug === fieldRepeaterSlug )
				if ( undefined === repeater ) return
				mainState.onChange( repeater.value, fieldRepeaterSlug )
				repeater.children.forEach( child => {
					mainState.onChange( mainState.fields.find( x => x.slug === child.slug ).value, child.slug )
				} )
			} }
		>
		</Button>
	)
}

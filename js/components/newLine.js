import { RemoveButton } from './removeButton'
import { MoveUpButton } from './moveUpButton'
import { MoveDownButton } from './moveDownButton'
import { MainContext } from '../app/context'
import { isFirstChild, isLastChild } from '../app/helpers'

export const NewLine = ( { field, condition } ) => {
	const mainState = React.useContext( MainContext )
 
	return (
		condition 
		? <div className='webaxones-field__repeater__newline'>
				{ ! isFirstChild( field.slug, mainState ) && <hr className='webaxones-field__repeater__separator' /> && <MoveUpButton fieldRepeaterSlug={ field.repeater } fieldSlug={ field.slug } /> }
				<RemoveButton fieldRepeaterSlug={ field.repeater } fieldSlug={ field.slug } />
				{ ! isLastChild( field.slug, mainState ) && <MoveDownButton fieldRepeaterSlug={ field.repeater } fieldSlug={ field.slug } /> }
			</div>
		: null
	)
}
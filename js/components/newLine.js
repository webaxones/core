import { RemoveButton } from './removeButton'

export const NewLine = ( { field, condition } ) => {
	return (
		condition 
		? <div className='wax-repeater-newline'>
				<hr className='wax-repeater-separator' />
				<RemoveButton fieldRepeater={ field.repeater } fieldSlug={ field.slug } />
			</div>
		: null
	)
}
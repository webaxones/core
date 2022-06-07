import { RemoveButton } from './removeButton'

export const NewLine = ( { field, condition } ) => {
	return (
		condition 
		? <div className='webaxones-field__repeater__newline'>
				<hr className='webaxones-field__repeater__separator' />
				<RemoveButton fieldRepeater={ field.repeater } fieldSlug={ field.slug } />
			</div>
		: null
	)
}
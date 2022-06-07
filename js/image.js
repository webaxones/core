import { Button } from '@wordpress/components'
import { MediaUpload } from '@wordpress/block-editor'
import { NewLine } from './components/newLine'
import { __ } from '@wordpress/i18n'
import { MainContext } from './app/context'

export const Image = ( { field, condition } ) => {
	const mainState = React.useContext( MainContext )

	const onSelectImage = media => {
		mainState.onChange( { id: media.id, url: media.url }, field.slug )
	}

	const onRemoveImage = () => {
		mainState.onChange( { id: 0, url: '' }, field.slug )
		ImagePreview( { id: 0, url: '' } )
	}

	const ImagePreview = media => {
		if ( media && typeof media === 'object' && '' !== media.url ) {
			return (
				<div className='webaxones-field__img-preview'>
					<img className='webaxones-field__img' src={ media.url } alt='Preview' />
				</div>
			)
		}
		return ''
	}

	const BtnUpload = ( field, open ) => {
		if ( field.value && typeof field.value === 'object' && '' !== field.value.url ) {
			return (
				<Button	isLink onClick={ open } className='webaxones-field__btn-img-upload'>
					{ __( 'Replace image', 'webaxones-core' ) }
				</Button>
			)
		} else {
			return (
				<Button	isSecondary icon='upload' onClick={ open } className='webaxones-field__btn-img-upload'>
					{ __( 'Select or upload an image', 'webaxones-core' ) }
				</Button>
			)
		}
	}

	return (
		<div className='webaxones-field webaxones-field__image'>
			<NewLine field={ field } condition={ condition } />
			<p className='webaxones-field__label'>{ field.label }</p>
			<p className='webaxones-field__help'>{ field.help }</p>
			{ ImagePreview( field.value ) }
			<MediaUpload
				onSelect={ onSelectImage }
				allowedTypes={ [ 'image' ] }
				value={ field.value }
				render={ ( { open } ) => (
					BtnUpload( field, open )
				) }
			/>
			{ ( field.value && typeof field.value === 'object' && '' !== field.value.url ) &&
					<Button className='webaxones-field__btn-img-remove' onClick={ onRemoveImage } isLink isDestructive>{ __( 'Remove image', 'webaxones-core' ) }</Button> }
		</div>
	)
}
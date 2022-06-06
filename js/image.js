
import { FormFileUpload, DropZone, Button } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import * as wpMediaUtils from '@wordpress/media-utils'
import { MainContext } from './app/context'
import { NewLine } from './components/newLine'

const ImagePreview = ( file ) => {
    if ( file && typeof file === 'object' && file.url !== '' ) {
        return <img className='wax-components-field__img' src={ file.url } alt='Preview' />
    }
	return ''
}

export const Image = ( { field, condition } ) => {
	const mainState = React.useContext( MainContext )
    const urlParams = new URLSearchParams( window.location.search )
    const file = urlParams.get( 'image' )

    if ( file && {} === field.value ) {
		mainState.onChange( file, field.slug )
    }

	const onRemoveImage = () => {
		mainState.onChange( { id: 0, url: '' }, field.slug )
		ImagePreview( { id: 0, url: '' } )
	}

	return ( 
		<>
		<NewLine field={ field } condition={ condition } />
		<div className='wax-components-field'>
			<div className='upload'>
				<p className='wax-components-field__label'>{ field.label }</p>
				<FormFileUpload
					className='wax-components-drop-zone'
					accept='image/*'
					icon='format-image'
					onChange={ ( file ) => {
						wpMediaUtils.uploadMedia( {
							filesList: file.target.files,
							onFileChange: ( [ fileObj ] ) => {
								if( 'undefined' !== typeof fileObj.slug ) {
									mainState.onChange( { id: fileObj.id, url: fileObj.url }, field.slug )
								}
							},
							onError: console.error,
						} )
					} }
					>
					{ __( 'Select an Image or Drop it here', 'webaxones-core' ) }
				</FormFileUpload>
				<DropZone
					onFilesDrop={ ( files ) => {
						wpMediaUtils.uploadMedia( {
							filesList: files[0],
							onFileChange: ( [ fileObj ] ) => {
								if( 'undefined' !== typeof fileObj.slug ) {
									mainState.onChange( { id: fileObj.slug, url: fileObj.url }, field.slug )
								}
							},
							onError: console.error,
						} )
					} }
				/>
				{ ImagePreview( field.value ) }
				{
					( field.value && typeof field.value === 'object' && field.value.url !== '' ) &&
					<div>
						<Button onClick={ onRemoveImage } isLink isDestructive>
							{ __( 'Remove image', 'webaxones-core' ) }
						</Button>
					</div>
				}
			</div>
		</div>
		</>
	)
}

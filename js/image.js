
import { FormFileUpload, DropZone, Button } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import * as wpMediaUtils from '@wordpress/media-utils'

const ImagePreview = ( file ) => {
    if ( file && typeof file === 'object' && file.url !== '' ) {
        return <img className='wax-components-field__img' src={ file.url } alt='Preview' />
    }
	return ''
}

export const Image = ( { fieldValue, field, onChange } ) => {
    const urlParams = new URLSearchParams( window.location.search )
    const file = urlParams.get( 'image' )

    if ( file && {} === fieldValue ) {
		onChange( file, field.id )
    }

	const onRemoveImage = () => {
		onChange( { id: 0, url: '' }, field.id )
		ImagePreview( { id: 0, url: '' } )
	}

	return ( 
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
								if( 'undefined' !== typeof fileObj.id ) {
									onChange( { id: fileObj.id, url: fileObj.url }, field.id )
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
								if( 'undefined' !== typeof fileObj.id ) {
									onChange( { id: fileObj.id, url: fileObj.url }, field.id )
								}
							},
							onError: console.error,
						} )
					} }
				/>
				{ ImagePreview( fieldValue ) }
				{
					( fieldValue && typeof fieldValue === 'object' && fieldValue.url !== '' ) &&
					<div>
						<Button onClick={ onRemoveImage } isLink isDestructive>
							{ __( 'Remove image', 'webaxones-core' ) }
						</Button>
					</div>
				}
			</div>
		</div>
	)
}

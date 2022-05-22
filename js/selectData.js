import { SearchControl, Spinner } from '@wordpress/components';
import { useState, render } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';
import { decodeEntities } from '@wordpress/html-entities'
import Select from 'react-select'

 
export const SelectData = ( { fieldValue, field, onChange } ) => {
    const [ searchTerm, setSearchTerm ] = useState( '' )

    const { pages, hasResolved } = useSelect(
        ( select ) => {
            const query = {}
            if ( searchTerm ) {
                query.search = searchTerm;
            }
            const selectorArgs = [ 'postType', 'post', query ]
            return {
                pages: select( coreDataStore ).getEntityRecords(
                    ...selectorArgs
                ),
                hasResolved: select( coreDataStore ).hasFinishedResolution(
                    'getEntityRecords',
                    selectorArgs
                ),
            };
        },
        [ searchTerm ]
    );

	let options =[]
	pages?.map( ( record ) => (
		options.push( { value:record.id, label:decodeEntities( record.title.rendered ) } )
	) )

	return (
		<Select
			options={ options }
			value={ fieldValue }
			onInputChange={ setSearchTerm }
			onChange={ ( value ) => {
				onChange( value, field.id )
			} }
		/>
    );
}


import { SearchControl, Spinner } from '@wordpress/components'
import { useState, render } from '@wordpress/element'
import { useSelect } from '@wordpress/data'
import { store as coreDataStore } from '@wordpress/core-data'
import { decodeEntities } from '@wordpress/html-entities'
import { AsyncPaginate } from 'react-select-async-paginate'


 
export const SelectData = ( { fieldValue, field, onChange } ) => {
	let hasMore = true

	const args = field.hasOwnProperty('args') ? field.args : {}
	const isMultiple = args.hasOwnProperty('is_multiple') ? args.is_multiple : false
	const data = args.hasOwnProperty('data') ? args.data : { kind: 'postType', name: 'page' }

    const [ searchTerm, setSearchTerm ] = useState( '' )
    const [ searchPage, setSearchPage ] = useState( 1 )

    const { records, hasResolved } = useSelect(
        ( select ) => {
            const query = {}

			if ( searchPage > 1 ) {
                query.page = searchPage
            }

            if ( searchTerm ) {
                query.search = searchTerm
				query.page = 1
            }

			const selectorArgs = [ data?.kind, data?.name, query ]

            return {
                records: select( coreDataStore ).getEntityRecords(
                    ...selectorArgs
                ),
                hasResolved: select( coreDataStore ).hasFinishedResolution(
                    'getEntityRecords',
                    selectorArgs
                ),
            }
        },
        [ searchTerm, searchPage ]
    )

	const getTheOptions = () => {
		let options =[]

		if ( 0 === records.length ) {
			hasMore = false
			return {
				options: [],
				hasMore
			}
		}

		if ( 1 === records.length ) {
			hasMore = false
			return {
				options: [ { value:records[0].id, label:decodeEntities( records[0].title.rendered ) } ],
				hasMore
			}
		}

		records?.map( ( record ) => (
			options.push( { value:record.id, label:decodeEntities( record.title.rendered ) } )
		) )

		if ( hasMore && '' === searchTerm ) {
			setSearchPage(searchPage + 1)
		}

		console.log('records',records)

		return {
			options: options,
			hasMore
		}
	}

	return (
		<>
		<p className='wax-components-field__label'>{ field.label }</p>
		<AsyncPaginate
			value={ fieldValue }
			isMultiple={ isMultiple }
			loadOptions={ getTheOptions	}
			onInputChange={ setSearchTerm }
			onChange={ ( value ) => {
				onChange( value, field.id )
			} }
		/>
		</>
    )
}


import { useState, render } from '@wordpress/element'
import { useSelect } from '@wordpress/data'
import { store as coreDataStore } from '@wordpress/core-data'
import { decodeEntities } from '@wordpress/html-entities'
import { AsyncPaginate } from 'react-select-async-paginate'
import { get } from 'lodash'

export const SelectDataScroll = ( { fieldValue, field, onChange } ) => {
	let hasMore = true

	const perPage = 10

	const args = field.hasOwnProperty('args') ? field.args : {}
	const isMultiple = args.hasOwnProperty('is_multiple') ? args.is_multiple : false
	const isClearable = args.hasOwnProperty('is_clearable') ? args.is_clearable : false
	const data = args.hasOwnProperty('data') ? args.data : { kind: 'postType', name: 'page' }
	const query = {}
	data.hasOwnProperty('query') && Object.assign( query, data.query )

    const [ searchTerm, setSearchTerm ] = useState( '' )
    const [ searchPage, setSearchPage ] = useState( 0 )

    const { records, hasResolved } = useSelect(
        ( select ) => {
			query.per_page = perPage
			query.page = 1

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
				hasMore: hasMore,
			}
		}

		if ( 1 === records.length ) {
			hasMore = false
			return {
				options: [ { value:records[0].id, label:decodeEntities( get( records[0], data.value ) ) } ],
				hasMore: hasMore,
			}
		}

		records?.forEach( ( record ) => {
			options.push( { value:record.id, label:decodeEntities( get( record, data.value ) ) } )
		} )

		if ( records.length < perPage ) {
			hasMore = false
		}

		if ( hasMore ) {
			setSearchPage(searchPage + 1)
		}

		return {
			options: options,
			hasMore: hasMore,
		}
	}

	return (
		<>
		<p className='wax-components-field__label'>{ field.label }</p>
		<AsyncPaginate
			value={ fieldValue }
			isMulti={ isMultiple }
			isClearable={ isClearable }
			loadOptions={ getTheOptions	}
			onInputChange={ setSearchTerm }
			onChange={ ( value ) => {
				onChange( value, field.id )
			} }
		/>
		</>
    )
}
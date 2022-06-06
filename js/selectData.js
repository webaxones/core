import { useState } from '@wordpress/element'
import { useSelect } from '@wordpress/data'
import { store as coreDataStore } from '@wordpress/core-data'
import { decodeEntities } from '@wordpress/html-entities'
import { __ } from '@wordpress/i18n'
import React, { Component } from 'react'
import Select from 'react-select'
import { get } from 'lodash'
import { MainContext } from './app/context'
import { NewLine } from './components/newLine'

export const SelectData = ( { field, condition } ) => {
	const mainState = React.useContext( MainContext )
	const args = field.hasOwnProperty('args') ? field.args : {}
	const isMultiple = args.hasOwnProperty('is_multiple') ? args.is_multiple : false
	const isClearable = args.hasOwnProperty('is_clearable') ? args.is_clearable : false
	const data = args.hasOwnProperty('data') ? args.data : { kind: 'postType', name: 'page' }
	const query = {}
	data.hasOwnProperty('query') && Object.assign( query, data.query )

    const [ searchTerm, setSearchTerm ] = useState( '' )

	const { records, hasResolved } = useSelect(
        ( select ) => {
			if ( searchTerm ) {
                query.search = searchTerm
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
        [ searchTerm ]
    )

	let options = []

	if ( ! hasResolved ) {
		options.push( { value: 0, label: 'Loading...' } )
	}

	records?.forEach( ( record ) => {
		options.push( { value:record.id, label:decodeEntities( get( record, data.value ) ) } )
	} )

	return (
		<>
		<NewLine field={ field } condition={ condition } />
		<div className='wax-components-field'>
			<p className='wax-components-field__label'>{ field.label }</p>
			<Select
				value={ field.value || '' }
				isMulti={ isMultiple }
				isClearable={ isClearable }
				options={ options }
				onInputChange={ setSearchTerm }
				onChange={ ( value ) => {
					mainState.onChange( value, field.slug )
				} }
			/>
		</div>
		</>
	)
}

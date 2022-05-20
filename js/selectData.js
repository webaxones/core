import { Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data'
import { store as coreDataStore } from '@wordpress/core-data'
import apiFetch from '@wordpress/api-fetch'
import { createReduxStore, register } from '@wordpress/data'
import { decodeEntities } from '@wordpress/html-entities'
import { __ } from '@wordpress/i18n'
import React, { Component } from 'react'
import Select from 'react-select'

export const SelectData = ( { fieldValue, field, onChange } ) => {

	const args = field.hasOwnProperty('args') ? field.args : {}
	const isMultiple = args.hasOwnProperty('is_multiple') ? args.is_multiple : false
	const data = args.hasOwnProperty('data') ? args.data : { kind: 'postType', name: 'page' }
	
	const { records, hasResolved } = useSelect(
        ( select ) => {
            const query = {
				'status': 'publish', 
				'per_page': -1, 
			}
            const selectorArgs = [ data?.kind, data?.name, query ];
            return {
                records: select( coreDataStore ).getEntityRecords(
                    ...selectorArgs
                ),
                hasResolved: select( coreDataStore ).hasFinishedResolution(
                    'getEntityRecords',
                    selectorArgs
                ),
            };
        },
        []
    )

	let options = []

	if ( ! records?.length ) {
		options.push( { value: 0, label: 'Loading...' } )
    }

	records?.map( ( record ) => (
		options.push( { value:record.id, label:decodeEntities( record.title.rendered ) } )
	) )

	if ( ! hasResolved ) {
        return <Spinner />;
    }

	return <RecordsList records={ options } isMultiple={ isMultiple } fieldValue={ fieldValue } field={ field } onChange={ onChange } />
}

const RecordsList = ( { records, isMultiple, fieldValue, onChange, field } ) => {
	return (
		<>
			<p className='wax-components-field__label'>{ field.label }</p>
			<Select
			options={ records }
			isMulti={ isMultiple }
			value={ fieldValue || '' }
			onChange={ ( value ) => {
				onChange( value, field.id )
			} }
			/>
		</>
	)
}
import { useSelect } from '@wordpress/data'
import { store as coreDataStore } from '@wordpress/core-data'
import { decodeEntities } from '@wordpress/html-entities'
import { __ } from '@wordpress/i18n'
import React, { Component } from 'react'
import Select from 'react-select'

export const SelectData = ( { fieldValue, field, onChange } ) => {
	const args = field.hasOwnProperty('args') ? field.args : {}
	const isMultiple = args.hasOwnProperty('is_multiple') ? args.is_multiple : false
	const data = args.hasOwnProperty('data') ? args.data : { kind: 'postType', name: 'page' }
	
	const records = useSelect(
        select =>
            select( coreDataStore ).getEntityRecords( data?.kind, data?.name ),
        []
    )

	let options = []

	if ( ! records?.length ) {
		options.push( { value: 0, label: 'Loading...' } )
    }

	records?.map( ( record ) => (
		options.push( { value:record.id, label:decodeEntities( record.title.rendered ) } )

	) )

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
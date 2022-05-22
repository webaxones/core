import { Spinner } from '@wordpress/components'
import { useState } from '@wordpress/element'
import { useSelect } from '@wordpress/data'
import { store as coreDataStore } from '@wordpress/core-data'
import { decodeEntities } from '@wordpress/html-entities'
import { __ } from '@wordpress/i18n'
import React, { Component } from 'react'
import Select from 'react-select'
import apiFetch from '@wordpress/api-fetch'
import AsyncSelect from 'react-select/async'
import { AsyncPaginate } from 'react-select-async-paginate'

export const SelectData = ( { fieldValue, field, onChange } ) => {

	const args = field.hasOwnProperty('args') ? field.args : {}
	const isMultiple = args.hasOwnProperty('is_multiple') ? args.is_multiple : false
	const data = args.hasOwnProperty('data') ? args.data : { kind: 'postType', name: 'page' }
	const inputValue = fieldValue

	const sleep = ms =>
	new Promise(resolve => {
	  setTimeout(() => {
		resolve();
	  }, ms);
	});

	// DEBUT

	const [value, handleOnChange] = useState(null)

	const { records, hasResolved } = useSelect(
        ( select ) => {
            const query = {
				'per_page': -1, 
			}

			if ( inputValue ) {
				query.search = inputValue
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
        []
    )

	const loadOptions = async (inputValue, prevOptions) => {
		await sleep(1000)

		// const records = await apiFetch({ path: `/wp/v2/posts?per_page=100` })

		let filteredOptions

		if (!inputValue) {
			filteredOptions = records;
		} else {
			const searchLower = inputValue.toLowerCase()

			filteredOptions = options.filter(({ label }) =>
				label.toLowerCase().includes(searchLower)
			)
		}

		const hasMore = filteredOptions.length > prevOptions.length + 10
		const slicedOptions = filteredOptions.slice(
			prevOptions.length,
			prevOptions.length + 10
		)

		return {
			options: slicedOptions,
			hasMore
		}
	}

	return (
		<>
			<AsyncPaginate
				cacheOptions
				value={value}
				loadOptions={loadOptions}
				getOptionLabel={record => record.title.rendered}
				getOptionValue={record => record.id}
				onChange={ ( value ) => {
					handleOnChange
					onChange( [value.id, value.title.rendered], field.id )
				} }
			/>
		</>
	)
}

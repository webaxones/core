import { render, useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import api from '@wordpress/api'
import { Button, Icon, TabPanel, Panel, PanelBody, PanelRow, Placeholder, SelectControl, Spinner, TextControl, ToggleControl } from '@wordpress/components'
import { dispatch } from '@wordpress/data'
import { Text } from './text.js'
import { Notices } from './notices.js'

console.log(webaxonesApps);

const App = () => {

	const [fields, setFields] = useState( [] )
	const [tabs, setTabs] = useState( [] )
	const [tabSelected, setTabSelected] = useState( webaxonesApps[0][0].group )
  
	useEffect(() => {
		const getData = async () => {
			const response = await api.loadPromise.then( () => {
				const settings = new api.models.Settings()
				const settingsGroup = settings.fetch().then( ( response ) => {
					const data = []
					webaxonesApps.forEach( group => {
						group.forEach( field => {
							data.push(
								{
									id: field.slug,
									label: field.label,
									help: field.help,
									value: response[ field.slug ],
									tab: field.group
								}
							)
						} )
					} )
					setFields(data)
				} )
			} )
		}
		getData()
	}, [] )



	useEffect( () => {
		const getTabs = async () => {
			const response = await api.loadPromise.then( () => {
				const settings = new api.models.Settings()
				const settingsGroup = settings.fetch().then( ( response ) => {
					const data = []
					webaxonesApps.forEach( group => {
						data.push(
							{
								name: group[0].group,
								title: group[0].group_name,
								className: `${group[0].group}__tab`,
							}
						)
					} )
					setTabs( data )
				} )
			} )
		}
		getTabs()
	}, [] )

	const onChangeField = ( { value, id } ) => {
	  setFields( (prevState ) => {
		return prevState.map( ( item ) => {
		  if (item.id !== id) {
			return item;
		  }
		  return {
			...item,
			value: value
		  };
		});
	  });
	};

	return (
		<div className='App'>
			<TabPanel tabs={ tabs } onSelect={ ( tab ) => setTabSelected( tab.name ) }>
				{ ( tab ) => <>{ tab.children }</> }
			</TabPanel>
			{ fields.map( ( field, key ) => {
				if ( field.tab !== tabSelected ) {
					return null;
				}
				return <Text key={ key } fieldValue={ field.value } field={ field } onChange={ onChangeField } />
			} ) }
		</div>
	  )
}

document.addEventListener( 'DOMContentLoaded', () => {
    const htmlOutput = document.getElementById( 'wax-company-settings__content' )
    if ( htmlOutput ) {
        render(
            <App />,
            htmlOutput
        );
    }
});

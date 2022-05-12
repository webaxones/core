import api from '@wordpress/api'
import { Button, Icon, TabPanel, Panel, PanelBody, PanelRow, Placeholder, SelectControl, Spinner, TextControl, ToggleControl } from '@wordpress/components'
import { dispatch } from '@wordpress/data'
import { render, Component } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Text } from './text.js'
import { Notices } from './notices.js'

const settingsGroup = webaxonesApps[0]

const onSelect = ( tabName ) => {
	console.log( 'Selecting tab', tabName );
}

class App extends Component {
    constructor() {
        super( ...arguments )

		this.state = {}
		this.tabs = []

		webaxonesApps.forEach( group => {
			group.fields.forEach( field => {
				this.state[field.slug] = ''
			} )
			this.tabs.push({
				name: group.slug,
				title: group.label,
				content: group.fields.map( field => {
					return <Text key={ field.slug } onChange={ this.handleOnChange } state={ this.state } field={ field } />
				} )
			},)
		} )

		this.state['isAPILoaded'] = false
    }

	componentDidMount() {

        api.loadPromise.then( () => {
            this.settings = new api.models.Settings()

            const { isAPILoaded } = this.state

            if ( isAPILoaded === false ) {
                this.settings.fetch().then( ( response ) => {
					webaxonesApps.forEach( settingsGroup => {
						settingsGroup.fields.forEach( field => {
							this.setState( {
								[field.slug]: response[ field.slug ],
							} )
						} )
					} )	
					this.setState( {
						['isAPILoaded']: true
					} )
                } )
            }
        } )
    }

	handleOnChange = ( fieldSlug, value ) => {
		this.setState( { [fieldSlug]: value } )
	}

    render() {
		if ( ! this.state.isAPILoaded ) {
            return (
                <Placeholder>
                    <Spinner />
                </Placeholder>
            )
        } 

		return (
			<>
				{ ( webaxonesApps.length === 1 ) 
					? settingsGroup.fields.map( field => {
						return <Text key={ field.slug } onChange={ this.handleOnChange } state={ this.state } field={ field } />
					} )
					: <TabPanel
						className={ `${webaxonesApps[0]['page_slug']}__panel` }
						activeClass='active-tab'
						onSelect={ onSelect }
						tabs={ this.tabs }
					>
					{ ( tab ) => <div>{ tab.content }</div> }
					</TabPanel>
				}

				<Button
					isPrimary
					onClick={ () => {
						const values = this.state
						delete values.isAPILoaded

						const settings = new api.models.Settings( values )
						settings.save()

						dispatch('core/notices').createNotice(
							'success',
							__( 'Settings Saved', 'webaxones-core' ),
							{
								type: 'snackbar',
								isDismissible: true,
							}
						)
					} }
				>
					{ __( 'Save', 'webaxones-core' ) }
				</Button>

				<div className="wax-company-settings__notices">
                    <Notices/>
                </div>
			</>
        )
    }
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

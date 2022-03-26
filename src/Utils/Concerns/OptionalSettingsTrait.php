<?php

namespace WaxCustom\Concerns;

trait OptionalSettingsTrait
{
	/**
	 * Input settings given for custom content declaration
	 *
	 * @var array
	 */
	private array $inputSettings;

	/**
	 * Optional input settings in addition to those required
	 *
	 * @var array
	 */
	private array $arguments = [];

	/**
	 * Get input settings
	 *
	 * @return array
	 */
	public function getSettings(): array
	{
		return $this->inputSettings;
	}

	/**
	 * Add optionnal settings
	 *
	 * @param  array $options
	 * @param  array $data
	 * @param  array $inputSettings
	 *
	 * @return array
	 */
	public function AddPassedOptions( array $options, array $data, array $inputSettings ): array
	{
		$this->inputSettings = $inputSettings;
		return array_merge( $data, $this->getPassedOptions( $options ) );
	}

	/**
	 * Get values of Optional settings
	 *
	 * @param  array  $options
	 *
	 * @return array
	 */
	private function getPassedOptions( array $options ): array
	{
		array_walk(
			$options,
			function ( $option )
			{
				if ( array_key_exists( $option, $this->getSettings() ) ) {
					$inputSettings              = $this->getSettings();
					$this->arguments[ $option ] = $inputSettings[ $option ];
				}
			}
		);
		return $this->arguments;
	}
}

<?php

namespace Webaxones\Core;

defined( 'ABSPATH' ) || exit;

use DI\Container;

require 'vendor/autoload.php';

public class DIContainer
{
	private Container $container;

	public function __construct()
	{
		$container = new Container();
	}
}

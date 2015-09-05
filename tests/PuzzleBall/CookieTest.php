<?php

namespace PuzzleBall\Tests;

require_once __DIR__ . '/../../src/PuzzleBall.php';

class CookieTest extends \PHPUnit_Framework_TestCase
{
	public function testBasic()
	{
		$_GET['cookie'] = 'yes';
		$_COOKIE['name'] = 'foobar';

		$getrule = array(
			'cookie' => array( 'type' => \PuzzleBall::ALPHANUM )
		);
		$cookierule = array(
			'name' => array( 'type' => \PuzzleBall::ALPHANUM )
		);

		$ball = new \PuzzleBall(
			array(
				'GET' => $getrule,
				'COOKIE' => $cookierule,
			)
		);

		$this->assertEquals('yes', $ball->cookie);

		$this->assertEquals('foobar', $ball->cookie('name'));
	}

}

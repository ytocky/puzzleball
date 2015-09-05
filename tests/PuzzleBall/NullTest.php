<?php

namespace PuzzleBall\Tests;

require_once __DIR__ . '/../../src/PuzzleBall.php';

class NullTest extends \PHPUnit_Framework_TestCase
{
	public function testNormalCase()
	{
		$rules = array(
			'GET' => array(
				'cmd' => array(
					'type'=>\PuzzleBall::NUMBER,
					'length'=>array('min'=>0, 'max'=>3)
				),
			),
		);

		$_GET = array(
			'cmd' => '123',
			'str' => 'abc',
		);
		$_POST = array(
			'name' => 'my name',
		);
		$_ENV = array(
			'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0',
		);
		$ball = new \PuzzleBall($rules);
		$this->assertNull($_GET);
		$this->assertNull($_POST);
		$this->assertNull($_ENV);

		$rules2 = array(
			'POST' => array(
				'name' => array (
					'type'=>\PuzzleBall::PATTERN,
					'pattern'=>'/\A[a-zA-Z0-9 \,\']+\Z/'
				),
			),
		);
		$ball->add($rules2);
		$this->assertEquals('my name', $ball->name);

		$rules3 = array(
			'ENV' => array(
				'HTTP_USER_AGENT' => array (
					'type'=>\PuzzleBall::PATTERN,
					'pattern'=>'/\A[a-zA-Z0-9 \.\,\'\:\;\/\(\)]+\Z/'
				),
			),
		);
		$ball->add($rules3);
		$this->assertEquals('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0', $ball->HTTP_USER_AGENT);

		$this->assertEquals('123', $ball->cmd);
	}

}

<?php

namespace PuzzleBall\Tests;

require_once __DIR__ . '/../../src/PuzzleBall.php';

class GetTest extends \PHPUnit_Framework_TestCase
{
	public function testNormalCase()
	{
		$rule = array(
			'GET' => array(
				'id' => array(
					'type' => \PuzzleBall::PATTERN,
					'pattern' => '/^\d{1,4}$/',
				),
				'str' => array(
					'type' => \PuzzleBall::PATTERN,
					'pattern' => '/^[a-z]{1,5}$/',
				),
			),
		);

		$_GET = array(
			'id' => '123',
			'str' => 'abc',
		);
		$ball = new \PuzzleBall($rule);
		$this->assertEquals($ball->id, 123);
		$this->assertEquals($ball->str, 'abc');
	}

	// 長さが異常
	public function testWrongLengthCase()
	{
		$rule = array(
			'GET' => array(
				'id' => array(
					'type' => \PuzzleBall::PATTERN,
					'pattern' => '/^\d{1,4}$/',
				),
				'str' => array(
					'type' => \PuzzleBall::PATTERN,
					'pattern' => '/^[a-z]{1,5}$/',
				),
			),
		);

		$_GET = array(
			'id' => '123456',
			'str' => 'abcdefg',
		);
		$exception = null;
		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rule, $conf);
		$this->assertNull($ball->id);
		$this->assertNull($ball->str);
	}

}

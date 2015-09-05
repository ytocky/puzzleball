<?php

namespace PuzzleBall\Tests;

require_once __DIR__ . '/../../src/PuzzleBall.php';

class BasicTest extends \PHPUnit_Framework_TestCase
{
	public function testNormalCase()
	{
		$rules = array(
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
		$ball = new \PuzzleBall($rules);
		$this->assertEquals($ball->id, 123);
		$this->assertEquals($ball->str, 'abc');
	}

	// throw exception
	public function testWrongLengthCaseAndException()
	{
		$rules = array(
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
		$this->setExpectedException('PuzzleBall\BallException');

		$ball = new \PuzzleBall($rules);
	}

	// 
	public function testWrongLengthCaseAndFallback()
	{
		$rules = array(
			'GET' => array(
				'id' => array(
					'type' => \PuzzleBall::PATTERN,
					'pattern' => '/^\d{1,4}$/',
					'fallback' => -1,
				),
				'str' => array(
					'type' => \PuzzleBall::PATTERN,
					'pattern' => '/^[a-z]{1,5}$/',
					'fallback' => '',
				),
			),
		);

		$_GET = array(
			'id' => '123456',
			'str' => 'abcdefg',
		);

		$ball = new \PuzzleBall($rules);

		$this->assertEquals(-1, $ball->id);
		$this->assertEquals('', $ball->str);
	}

	// 
	public function testWrongLengthCaseBehavior()
	{
		$rules = array(
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

		$conf['behavior'] = \PuzzleBall::ONGOING;

		$_GET = array(
			'id' => '123456',
			'str' => 'abcdefg',
		);

		$ball = new \PuzzleBall($rules, $conf);

		$this->assertNull($ball->id);
		$this->assertNull($ball->str);
	}

	public function testNotExistParam()
	{
		$rules = array(
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

		$_GET = array();
		$ball = new \PuzzleBall($rules);

		$this->assertNull($ball->id);
		$this->assertNull($ball->str);
	}

	public function testNotExistParam2()
	{
		$rules = array(
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

		$_GET = array('id'=>123);
		$ball = new \PuzzleBall($rules);

		$this->assertEquals(123, $ball->id);
		$this->assertNull($ball->str);
	}

}

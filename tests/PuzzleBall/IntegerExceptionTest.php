<?php

namespace PuzzleBall\Tests;

class IntegerExceptionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @expectedException PuzzleBall\BallException
	 */
	public function testNew()
	{
		$_GET = array(
			'ok_id1' => '1',
			'ok_id2' => '40',
			'ok_id3' => '99',

			'ng_id1' => 'str',
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_id = array(
					'type' => \PuzzleBall::INTEGER,
			        'range' => array('min'=>1, 'max'=>100)
		);

		$rules = array(
			'GET' => array(
				'ok_id1'  => $rule_id,
				'ok_id2'  => $rule_id,
				'ok_id3'  => $rule_id,

				'ng_id1'  => $rule_id,
			),
		);

		$ball = new \PuzzleBall($rules);
	}

}

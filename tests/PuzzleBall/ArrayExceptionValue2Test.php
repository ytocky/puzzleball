<?php

namespace PuzzleBall\Tests;

class ArrayExceptionValue2Test extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @expectedException PuzzleBall\BallException
	 */
	public function testNew()
	{
		$str = 'a[]=11&a[]=2&a[]=3';
		parse_str($str, $_GET);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_val = array( 'type'=>\PuzzleBall::INTEGER, 'range'=>array('min'=>1, 'max'=>10));
		$rules['GET'] = array(
			'a[]' => array(
				'maxCount' => 4,
				'value' => $rule_val,
			),
		);

		$ball = new \PuzzleBall($rules);
	}

}

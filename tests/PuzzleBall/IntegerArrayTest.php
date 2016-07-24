<?php

namespace PuzzleBall\Tests;

class IntegerArrayTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$str = 'a[]=1&a[]=2&a[]=3&b[1]=4&b[10]=5&c[key]=6&c[name]=7';
		parse_str($str, $_GET);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$rule_val = array( 'type'=>\PuzzleBall::INTEGER, 'range'=>array('min'=>1, 'max'=>10));
		$rules['GET'] = array(
			'a[]' => array(
				'index' => array( 'type'=>\PuzzleBall::INTEGER, 'range'=>array('min'=>0, 'max'=>3) ),
				'value' => $rule_val,
			),
			'b[]' => array(
				'index' => array( 'type'=>\PuzzleBall::INTEGER, 'range'=>array('min'=>0, 'max'=>10) ),
				'value' => $rule_val,
			),
			'c[]' => array(
				'index' => array( 'type'=>\PuzzleBall::WHITELIST, 'list'=>array('key','name') ),
				'value' => $rule_val,
			),
		);

		$config['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $config);

		return $ball;
	}

	/**
	 * @depends testNew
	 */
	public function testOKValue($ball) {
		$this->assertSame(1,$ball->a[0]);
		$this->assertSame(2,$ball->a[1]);
		$this->assertSame(3,$ball->a[2]);
		$this->assertSame( array(0,1,2) , array_keys($ball->a));

		$this->assertSame(4,$ball->b[1]);
		$this->assertSame(5,$ball->b[10]);
		$this->assertSame( array(1,10), array_keys($ball->b) );

		$this->assertSame(6,$ball->c['key']);
		$this->assertSame(7,$ball->c['name']);
		$this->assertSame( array('key','name'), array_keys($ball->c) );
	}

}

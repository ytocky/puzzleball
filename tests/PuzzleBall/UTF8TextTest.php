<?php

namespace PuzzleBall\Tests;

class UTF8TextTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testNew()
	{
		$_GET = array(
			'ok_val1' => '1',
			'ok_val2' => '1234567890',
			'ok_val3' => 'A',
			'ok_val4' => 'aBCDEFGHIJ',
			'ok_val5' => 'あ',
			'ok_val6' => 'あいうえおかきくけこ',
			'ok_val7' => '壱弐参四伍六七八九拾',
			'ok_val8' => 'ABCあさがおDEF',
			'ok_val9' => '<script>',
			'ok_val10' => 'alert("1")',
			'ok_val11' => "\xE3\x81\x93" . "\xE3\x82\x93" . "\xE3\x81\xAB". "\xE3\x81\xA1" . "\xE3\x81\xAF",

			'ok_val12' => 'ABC DEF',		// Hankaku-Space
			'ok_val13' => 'ABC		DEF',   // Tab
			'ok_val14' => 'ABC' . "\x0D" . 'DEF',
			'ok_val15' => 'ABC　　DEF',   // Zenkaku-Space

			'ng_val1' => '壱弐参四伍六七八九拾一',
			'ng_val2' => 'ABCD5678901',

			'ng_val5' => "\x00",
			'ng_val6' => "\x01",
			'ng_val7' => "\x02",
			'ng_val8' => "\x03",
			'ng_val9' => "\x04",
			'ng_val10' => "\x05",
			'ng_val11' => "\x06",
			'ng_val12' => "\x07",
			'ng_val13' => "\x08",
			// 'ng_val14' => "\x09", TAB
			'ng_val15' => "\x0b",
			'ng_val16' => "\x0c",
			'ng_val17' => "\x0e",
			'ng_val18' => "\x0f",
			'ng_val19' => "\x10",
			'ng_val20' => "\x11",
			'ng_val21' => "\x12",
			'ng_val22' => "\x13",
			'ng_val23' => "\x14",
			'ng_val24' => "\x15",
			'ng_val25' => "\x16",
			'ng_val26' => "\x17",
			'ng_val27' => "\x18",
			'ng_val28' => "\x19",
			'ng_val29' => "\x1b",
			'ng_val30' => "\x1c",
			'ng_val31' => "\x1e",
			'ng_val32' => "\x1f",
		);

		require_once __DIR__ . '/../../src/PuzzleBall.php';

		$this->assertFalse(isset($_GET));
		$this->assertFalse(isset($_POST));
		$this->assertFalse(isset($_COOKIE));
		$this->assertFalse(isset($_ENV));

		$rule_val = array(
					'type' => \PuzzleBall::UTF8TEXT,
			        'length' => array('min'=>1, 'max'=>10)
		);

		$rules = array(
			'GET' => array(
				'ok_val1'  => $rule_val,
				'ok_val2'  => $rule_val,
				'ok_val3'  => $rule_val,
				'ok_val4'  => $rule_val,
				'ok_val5'  => $rule_val,
				'ok_val6'  => $rule_val,
				'ok_val7'  => $rule_val,
				'ok_val8'  => $rule_val,
				'ok_val9'  => $rule_val,
				'ok_val10'  => $rule_val,
				'ok_val11'  => $rule_val,
				'ok_val12'  => $rule_val,
				'ok_val13'  => $rule_val,
				'ok_val14'  => $rule_val,
				'ok_val15'  => $rule_val,

				'ng_val1'  => $rule_val,
				'ng_val2'  => $rule_val,
				'ng_val3'  => $rule_val,

				'ng_val5'  => $rule_val,
				'ng_val6'  => $rule_val,
				'ng_val7'  => $rule_val,
				'ng_val8'  => $rule_val,
				'ng_val9'  => $rule_val,
				'ng_val10'  => $rule_val,
				'ng_val11'  => $rule_val,
				'ng_val12'  => $rule_val,
				'ng_val13'  => $rule_val,
				'ng_val15'  => $rule_val,
				'ng_val16'  => $rule_val,
				'ng_val17'  => $rule_val,
				'ng_val18'  => $rule_val,
				'ng_val19'  => $rule_val,
				'ng_val20'  => $rule_val,
				'ng_val21'  => $rule_val,
				'ng_val22'  => $rule_val,
				'ng_val23'  => $rule_val,
				'ng_val24'  => $rule_val,
				'ng_val25'  => $rule_val,
				'ng_val26'  => $rule_val,
				'ng_val27'  => $rule_val,
				'ng_val28'  => $rule_val,
				'ng_val29'  => $rule_val,
				'ng_val30'  => $rule_val,
				'ng_val31'  => $rule_val,
				'ng_val32'  => $rule_val,
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
		$this->assertEquals('1', $ball->ok_val1);
		$this->assertEquals('1234567890', $ball->ok_val2);
		$this->assertEquals('A', $ball->ok_val3);
		$this->assertEquals('aBCDEFGHIJ', $ball->ok_val4);
		$this->assertEquals('あ', $ball->ok_val5);
		$this->assertEquals('あいうえおかきくけこ', $ball->ok_val6);
		$this->assertEquals('壱弐参四伍六七八九拾', $ball->ok_val7);
		$this->assertEquals('ABCあさがおDEF', $ball->ok_val8);
		$this->assertEquals('<script>', $ball->ok_val9);
		$this->assertEquals('alert("1")', $ball->ok_val10);
		$this->assertEquals('こんにちは', $ball->ok_val11);

		$this->assertEquals('ABC DEF', $ball->ok_val12);
		$this->assertEquals('ABC		DEF', $ball->ok_val13);
		$this->assertEquals('ABC' . "\x0D" . 'DEF', $ball->ok_val14);
		$this->assertEquals('ABC　　DEF', $ball->ok_val15);
	}

	/**
	 *  @depends testNew
	 */
	public function testNGValue($ball)
	{
		$this->assertNull($ball->ng_val1);
		$this->assertNull($ball->ng_val2);
		$this->assertNull($ball->ng_val3);

		$this->assertNull($ball->ng_val5);
		$this->assertNull($ball->ng_val6);
		$this->assertNull($ball->ng_val7);
		$this->assertNull($ball->ng_val8);
		$this->assertNull($ball->ng_val9);
		$this->assertNull($ball->ng_val10);
		$this->assertNull($ball->ng_val11);
		$this->assertNull($ball->ng_val12);
		$this->assertNull($ball->ng_val13);
		$this->assertNull($ball->ng_val15);
		$this->assertNull($ball->ng_val16);
		$this->assertNull($ball->ng_val17);
		$this->assertNull($ball->ng_val18);
		$this->assertNull($ball->ng_val19);
		$this->assertNull($ball->ng_val20);
		$this->assertNull($ball->ng_val21);
		$this->assertNull($ball->ng_val22);
		$this->assertNull($ball->ng_val23);
		$this->assertNull($ball->ng_val24);
		$this->assertNull($ball->ng_val25);
		$this->assertNull($ball->ng_val26);
		$this->assertNull($ball->ng_val27);
		$this->assertNull($ball->ng_val28);
		$this->assertNull($ball->ng_val29);
		$this->assertNull($ball->ng_val30);
		$this->assertNull($ball->ng_val31);
		$this->assertNull($ball->ng_val32);
	}
}

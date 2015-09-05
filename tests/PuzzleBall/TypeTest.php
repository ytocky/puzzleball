<?php

namespace PuzzleBall\Tests;

require_once __DIR__ . '/../../src/PuzzleBall.php';

class TypeTest extends \PHPUnit_Framework_TestCase
{

	public function testIntegerRange()
	{

		$idrule = array(
			'type' => \PuzzleBall::INTEGER,
			'range' => array( 'min' => 1, 'max' => 10),
		);
		$rules = array(
			'GET' => array(

				'id1' => $idrule,
				'id2' => $idrule,
				'id3' => $idrule,
				'id4' => $idrule,

			)
		);

		$str = 'id1=0&id2=1&id3=10&id4=11';
		parse_str($str, $_GET);

		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertNull($ball->id1);
		$this->assertEquals($ball->id2, 1);
		$this->assertEquals($ball->id3, 10);
		$this->assertNull($ball->id4);
	}

	public function testIntegerLength()
	{

		$idrule = array(
			'type' => \PuzzleBall::INTEGER,
			'length' => array( 'min' => 1, 'max' => 2),
		);
		$rules = array(
			'GET' => array(

				'id1' => $idrule,
				'id2' => $idrule,
				'id3' => $idrule,
				'id4' => $idrule,
				'id5' => $idrule,
				'id6' => $idrule,
				'id7' => $idrule,
				'id8' => $idrule,

			)
		);

		$_GET = array(
			'id1' => '0',
			'id2' => '1',
			'id3' => '10',
			'id4' => '100',
			'id5' => 'a',
			'id6' => '2a',
			'id7' => 'a3',
			'id8' => '',
		);
		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertEquals($ball->id1, 0);
		$this->assertEquals($ball->id2, 1);
		$this->assertEquals($ball->id3, 10);
		$this->assertNull($ball->id4);
		$this->assertNull($ball->id5);
		$this->assertNull($ball->id6);
		$this->assertNull($ball->id7);
		$this->assertNull($ball->id8);
	}

	public function testNumberLength()
	{

		$rule = array(
			'type' => \PuzzleBall::NUMBER,
			'length' => array( 'min' => 1, 'max' => 5),
		);
		$rules = array(
			'GET' => array(

				'id1' => $rule,
				'id2' => $rule,
				'id3' => $rule,
				'id4' => $rule,
				'id5' => $rule,
				'id6' => $rule,
				'id7' => $rule,
				'id8' => $rule,
				'id9' => $rule,
				'id10' => $rule,
				'id11' => $rule,

			)
		);

		$_GET = array(
			'id1' => '0',
			'id2' => '0.0',
			'id3' => '.1',
			'id4' => '100.',
			'id5' => '50',
			'id6' => '3.14',
			'id7' => '-3.14',
			'id8' => '+3.14',
			'id9' => '-3.141',
			'id10' => 'a+1',
			'id11' => '+1e2',
		);
		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertEquals($ball->id1, 0);
		$this->assertEquals($ball->id2, 0);
		$this->assertEquals($ball->id3, 0.1);
		$this->assertEquals($ball->id4, 100);
		$this->assertEquals($ball->id5, 50);
		$this->assertEquals($ball->id6, 3.14);
		$this->assertEquals($ball->id7, -3.14);
		$this->assertEquals($ball->id8, 3.14);
		$this->assertNull($ball->id9);
		$this->assertNull($ball->id10);
		$this->assertNull($ball->id11);
	}

	public function testAlphabet()
	{

		$rule = array(
			'type' => \PuzzleBall::ALPHABET,
			'length' => array( 'min' => 1, 'max' => 3),
		);
		$rule2 = array(
			'type' => \PuzzleBall::ALPHABET,
			'length' => array( 'min' => 0, 'max' => 3),
		);
		$rules = array(
			'GET' => array(

				'name1' => $rule,
				'name2' => $rule,
				'name3' => $rule,
				'name4' => $rule,
				'name5' => $rule,
				'name6' => $rule,
				'name7' => $rule,
				'name8' => $rule2,

			)
		);

		$_GET = array(
			'name1' => 'a',
			'name2' => 'abc',
			'name3' => '0ab',
			'name4' => 'ab0',
			'name5' => '0',
			'name6' => 'abcd',
			'name7' => '',
			'name8' => '',
		);
		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertEquals($ball->name1, 'a');
		$this->assertEquals($ball->name2, 'abc');
		$this->assertNull($ball->name3);
		$this->assertNull($ball->name4);
		$this->assertNull($ball->name5);
		$this->assertNull($ball->name6);
		$this->assertNull($ball->name7);
		$this->assertEquals($ball->name8, '');
	}

	public function testAlphabetNumber()
	{

		$rule = array(
			'type' => \PuzzleBall::ALPHABET_NUMBER,
			'length' => array( 'min' => 1, 'max' => 3),
		);
		$rules = array(
			'GET' => array(

				'name1' => $rule,
				'name2' => $rule,
				'name3' => $rule,
				'name4' => $rule,
				'name5' => $rule,
				'name6' => $rule,
				'name7' => $rule,

			)
		);

		$_GET = array(
			'name1' => 'a',
			'name2' => 'abc',
			'name3' => '0ab',
			'name4' => 'ab0',
			'name5' => '0',
			'name6' => 'abcd',
			'name7' => '',
			'name8' => '',
		);
		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertEquals($ball->name1, 'a');
		$this->assertEquals($ball->name2, 'abc');
		$this->assertEquals($ball->name3, '0ab');
		$this->assertEquals($ball->name4, 'ab0');
		$this->assertEquals($ball->name5, '0');
		$this->assertNull($ball->name6);
		$this->assertNull($ball->name7);
	}

	public function testWhiteList()
	{

		$rule = array(
			'type' => \PuzzleBall::WHITELIST,
			'list' => array( 1, 10, 20, 30, 3.14 ),
		);
		$rules = array(
			'GET' => array(

				'id1' => $rule,
				'id2' => $rule,
				'id3' => $rule,
				'id4' => $rule,
				'id5' => $rule,
				'id6' => $rule,
				'id7' => $rule,
				'id8' => $rule,
				'id9' => $rule,
			)
		);

		$_GET = array(
			'id1' => '1',
			'id2' => '2',
			'id3' => '30',
			'id4' => '0',
			'id5' => '',
			'id6' => '1a',
			'id7' => 'a1',
			'id8' => '3.14',
			'id9' => '3.141',
		);

		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertEquals($ball->id1, 1);
		$this->assertNull($ball->id2);
		$this->assertEquals($ball->id3, 30);
		$this->assertNull($ball->id4);
		$this->assertNull($ball->id5);
		$this->assertNull($ball->id6);
		$this->assertNull($ball->id7);
		$this->assertEquals($ball->id8, 3.14);
		$this->assertNull($ball->id9);
	}

	public function testWhiteList2()
	{

		$rule = array(
			'type' => \PuzzleBall::WHITELIST,
			'list' => array( 1, "a", "abc", "" ),
		);
		$rules = array(
			'GET' => array(

				'id1' => $rule,
				'id2' => $rule,
				'id3' => $rule,
				'id4' => $rule,
				'id5' => $rule,
				'id6' => $rule,
				'id7' => $rule,
				'id8' => $rule,
				'id9' => $rule,
				'id10' => $rule,
			)
		);

		$_GET = array(
			'id1' => '1',
			'id2' => 'a',
			'id3' => 'abc',
			'id4' => '',
			'id5' => 'ab',
			'id6' => 'abcd',
			'id7' => '0',
		);

		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertEquals($ball->id1, 1);
		$this->assertEquals($ball->id2, 'a');
		$this->assertEquals($ball->id3, 'abc');
		$this->assertEquals($ball->id4, '');
		$this->assertNull($ball->id5);
		$this->assertNull($ball->id6);
		$this->assertNull($ball->id7);
	}

	public function testUTF8()
	{

		$rule = array(
			'type' => \PuzzleBall::UTF8,
			'length' => array( 'min' => 1, 'max' => 5),
		);
		$rules['GET'] = array(
			'name1' => $rule,
			'name2' => $rule,
			'name3' => $rule,
			'name4' => $rule,
			'name5' => $rule,
			'name6' => $rule,
			'name7' => $rule,
			'name8' => $rule,
			'name9' => $rule,
			'name10' => $rule,
			'name11' => $rule,
			'name12' => $rule,
			'name13' => $rule,
			'name14' => $rule,
			'name15' => $rule,
			'name16' => $rule,
			'name17' => $rule,
			'name18' => $rule,
			'name19' => $rule,
			'name20' => $rule,
			'name21' => $rule,
			'name22' => $rule,
			'name23' => $rule,
		);
		$_GET = array(
			'name1' => 'abc',
			'name2' => '123 4',
			'name3' => '0',
			'name4' => '',
			'name5' => 'あいうえお',
			'name6' => 'abcあい',
			'name7' => 'abcあいd',
			'name8' => '     ',
			'name9' => '      ',
			'name10' => 'あ　い',
			'name11' => "\xC0\xAF\xC0\xBCa",
			'name12' => "ab\ncd",
			'name13' => mb_convert_encoding("あいうえお", "sjis-win", "utf-8"),
			'name14' => mb_convert_encoding("あいうえお", "euc-jp", "utf-8"),
			'name15' => '　',
			'name16' => '漢字',
			'name17' => 'カタカナ',
			'name18' => 'Hi!',
			'name19' => 'Hmm, ',
			'name20' => 'わ～ん',
			'name21' => '(^-^)',
			'name22' => ';->',
			'name23' => '漢字\nカタカナ\nひらがな',
		);

		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertEquals($ball->name1, 'abc');
		$this->assertEquals($ball->name2, '123 4');
		$this->assertEquals($ball->name3, '0');
		$this->assertEquals($ball->name4, '');
		$this->assertEquals($ball->name5, 'あいうえお');
		$this->assertEquals($ball->name6, 'abcあい');
		$this->assertNull($ball->name7);
		$this->assertEquals($ball->name8, '     ');
		$this->assertNull($ball->name9);
		$this->assertEquals($ball->name10, 'あ　い');
		$this->assertNull($ball->name11);
		$this->assertNull($ball->name12);
		$this->assertNull($ball->name13);
		$this->assertNull($ball->name14);
		$this->assertEquals($ball->name15, '　');
		$this->assertEquals($ball->name16, '漢字');
		$this->assertEquals($ball->name17, 'カタカナ');
		$this->assertEquals($ball->name18, 'Hi!');
		$this->assertEquals($ball->name19, 'Hmm, ');
		$this->assertEquals($ball->name20, 'わ～ん');
		$this->assertEquals($ball->name21, '(^-^)');
		$this->assertEquals($ball->name22, ';->');
		$this->assertNull($ball->name23);
	}

	public function testUTF8TEXT()
	{

		$rule = array(
			'type' => \PuzzleBall::UTF8TEXT,
			'length' => array( 'min' => 1, 'max' => 5),
		);
		$rules['GET'] = array(
			'name1' => $rule,
			'name2' => $rule,
			'name3' => $rule,
			'name4' => $rule,
			'name5' => $rule,
			'name6' => $rule,
			'name7' => $rule,
			'name8' => $rule,
			'name9' => $rule,
			'name10' => $rule,
			'name11' => $rule,
			'name12' => $rule,
			'name13' => $rule,
			'name14' => $rule,
			'name15' => $rule,
			'name16' => $rule,
			'name17' => $rule,
			'name18' => $rule,
			'name19' => $rule,
			'name20' => $rule,
			'name21' => $rule,
			'name22' => $rule,
			'name23' => $rule,
		);
		$_GET = array(
			'name1' => 'abc',
			'name2' => '123 4',
			'name3' => '0',
			'name4' => '',
			'name5' => 'あいうえお',
			'name6' => 'abcあい',
			'name7' => 'abcあいd',
			'name8' => '     ',
			'name9' => '      ',
			'name10' => 'あ　い',
			'name11' => "\xC0\xAF\xC0\xBCa",
			'name12' => "ab\ncd",
			'name13' => mb_convert_encoding("あいうえお", "sjis-win", "utf-8"),
			'name14' => mb_convert_encoding("あいうえお", "euc-jp", "utf-8"),
			'name15' => '　',
			'name16' => '漢字',
			'name17' => 'カタカナ',
			'name18' => 'Hi!',
			'name19' => 'Hmm, ',
			'name20' => 'わ～ん',
			'name21' => '(^-^)',
			'name22' => ';->',
			'name23' => '漢字\nカタカナ\nひらがな',
		);

		$conf['behavior'] = \PuzzleBall::ONGOING;
		$ball = new \PuzzleBall($rules, $conf);

		$this->assertEquals($ball->name1, 'abc');
		$this->assertEquals($ball->name2, '123 4');
		$this->assertEquals($ball->name3, '0');
		$this->assertEquals($ball->name4, '');
		$this->assertEquals($ball->name5, 'あいうえお');
		$this->assertEquals($ball->name6, 'abcあい');
		$this->assertNull($ball->name7);
		$this->assertEquals($ball->name8, '     ');
		$this->assertNull($ball->name9);
		$this->assertEquals($ball->name10, 'あ　い');
		$this->assertNull($ball->name11);
		$this->assertEquals($ball->name12, "ab\ncd");
		$this->assertNull($ball->name13);
		$this->assertNull($ball->name14);
		$this->assertEquals($ball->name15, '　');
		$this->assertEquals($ball->name16, '漢字');
		$this->assertEquals($ball->name17, 'カタカナ');
		$this->assertEquals($ball->name18, 'Hi!');
		$this->assertEquals($ball->name19, 'Hmm, ');
		$this->assertEquals($ball->name20, 'わ～ん');
		$this->assertEquals($ball->name21, '(^-^)');
		$this->assertEquals($ball->name22, ';->');
	//	$this->assertEquals($ball->name23, '漢字\nカタカナ\nひらがな');
	}

}

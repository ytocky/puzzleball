# puzzleball
PuzzleBall is a PHP library that check incoming variables.

# 概要



# インストール

# 使い方の大雑把な説明

## 1. ルールを作る

    $rule['GET'] = array(
      'page' => array(
        'type' => PuzzleBall::NUMBER,
        'range' => array('min'=>1, 'max'=>20)
      ),
    );

PHP5.4以降なら

    $rule['GET'] = [
      'page' => [
        'type' => PuzzleBall::NUMBER,
        'range' => ['min'=>1, 'max'=>20]
      ]
    ];

## 2. ボールを作る

    $ball = new PuzzleBall($rule);

ルールをもとにボールを作り、同時にGETパラメータやクッキーなどを受け取ります。  
もしルール外の値が渡された場合は、例外(BallException)を投げます。  
つまりユーザーに入力にさせた値を送信する際は、変な値を送りつけないように、HTML5のフォームの機能を使ったり、JavaScriptでチェックするなどの、制限をしておくことを期待しています。  

## 3. 値をボールから取り出す

    $ball->page

## 4. $_GET, $_POST, $_COOKIEは使えない

$_GET は、NULLになります。  
外からの値は、必ずボールから取り出してください。  

# タイプ一覧

|PATTERN||
|INTEGER||
|ALPHABET||
|NUMBER||
|ALPHANUM||
|WHITELIST||
|UTF8||
|UTF8TEXT||

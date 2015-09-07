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

# ボールの作り方

```
$ball = new PuzzleBall($rule, $config);
```

$rule : ルール（後述）
$config : コンフィグ（後述）

# ルールの書きかた

```
$rule['種別'] = array(
	'パラメータ名' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
	'パラメータ名' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
    ....
);
```
または
```
$rule = array(
    '種別' => array(
	    'パラメータ名' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
	    'パラメータ名' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
        ....
    ),
);
```
など。

|名前|説明|
|----|----|
|種別|GET, POST, ENV, COOKIE|
|パラメータ名|その名の通り、パラメータ名。ids[]のように、[]を付けると、配列を求めるとして扱います。|
|タイプ|次の項目で説明|
|長さ指定|array( 'min'=>最小値, 'max'=>最大値)　省略可。|
|範囲指定|array( 'min'=>最小値, 'max'=>最大値)　省略可。|

おすすめの書き方は、
```
$pattern['name'] = array('type'=>PuzzleBall::UTF8, 'length'=>array('min'=>1, 'max'=>20);
$rule['POST'] = array(
    'myname' => $pattern['name'],
    'teacher_name' => $pattern['name'],
    'friends_name[]' => $pattern['name'],
);
```
のように、パラメータ名とは別個にルールを定義する感じ（名前なら、だれの名前であっても同じルールを適用するでしょ）。

# タイプ一覧

受け取るパラメータそれぞれに対し、どのような値を許容するかを指定します。    
* 文字種(type)
* 文字列の場合は、許容する文字列長(length)、
　数字の場合は、許容する値の範囲(range)、または許容する桁数(length)

rangeとlengthの両方を指定している場合は、rangeの指定のみをチェックします。   
rangeとlengthのどちらも指定していない場合は、lengthとして文字列は16文字、数字は3桁が指定されたものとして扱います。   

|type|説明|length指定|range指定|
|----|----|----------|---------|
|PuzzleBall::PATTERN|任意の正規表現によるチェック|なし|なし|
|PuzzleBall::INTEGER|整数のみ|あり|あり|
|PuzzleBall::ALPHABET|アルファベット(大文字小文字)のみ|あり|なし|
|PuzzleBall::NUMBER|数字(と+記号、-記号、小数点)のみ|あり|あり|
|PuzzleBall::ALPHANUM|上記アルファベットと数字のみ|あり|なし|
|PuzzleBall::WHITELIST|許容する値を列挙したリスト(ホワイトリスト)に含まれる値のみ|なし|なし|
|PuzzleBall::UTF8|UTF8の文字列(改行文字含まず)のみ|あり|なし|
|PuzzleBall::UTF8TEXT|UTF8の文字列(改行文字を許容)のみ|あり|なし|

# ルールに合わない値が送信されたら

new の時点で、PuzzleBall::BallException という例外を投げます。    
例外投げてほしくないときは、コンフィグで設定します（後述）。

# コンフィグの書き方

```
$config = array(
    '項目' => 設定値,
);
```

|項目|設定値|説明|
|----|------|----|
|behavior|PuzzleBall::EXCEPTION|例外を投げる(規定動作)|
||PuzzleBall::ONGOING|処理続行|
|fallback|behaviorとして処理続行を設定した場合に有効。ルールに合わない値が送信されたときの、代替値||

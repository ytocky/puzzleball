# PuzzleBall
PuzzleBall is a PHP library that check incoming variables.

# 概要

これは、バリデーション（入力値検証）とは異なるアプローチで、Webアプリケーションが期待したパラメータ値のみを受け取ることを保障する、ライブラリです。  

幼い時に遊んだ（かもしれない）知育玩具、丸や三角などの穴が開いたボール（以下、パズルボール）を思い出してみてください。このパズルボールには、丸や三角の形をしたピースを入れることができます。丸いピースは、三角形の穴には入りません。丸いピースは丸い穴に、三角のピースは三角形の穴に、四角いピースは四角形の穴にしか、入りません。ミニカーのおもちゃを入れようとしても、ボールにはそのような穴が開いていないので、入れることはできません。つまり、ボールの中には、ボールの持つ穴に適した形のピースのみが入っていることになります。

これと同じことを、Webアプリケーションでも行おうという試みです。

# 紹介

Webアプリケーションでは、一般に
> * 値を受け取る  
↓
* バリデーション（入力値検証）する  
↓
* 処理する

という順に処理を行います。バリデーションは、安全で問題の起こらないWebアプリケーションを開発する上で必須の処理です。しかし、実装時に漏れが生じたり、そもそも実装を怠ったりしやすい処理でもあります。そもそもパラメータの定義があいまいという場合すらあります。  
バリデーションが不十分であっても正常な値を受け取った時は正常に動作してしまいます。このことから、「バリデーション処理を実装すべし」というのは、実装時の **「お作法」** に留まっているのが現状ではないかと感じています。   

そこで、お作法ではなく、入力値検証をしないと、アプリケーションは受け取れないようにします。  
このライブラリは、requireした時点で、$_GET, $_POST, $_COOKIE, $_ENVをunset（未定義状態に）します。つまり、
~~~
require_once 'PuzzleBall.php';
var_dump($_GET);
~~~
を実行すると、

> Notice: Undefined variable: _GET in ***.php  

となります。これでは、アプリケーションは、何も値を受け取れませんね。

ここで、「このような値であれば受け取る」というルールを定義し、PuzzleBallを作成します。パズルボールに穴をあけるように。そして、ボールの中に入っているパーツを取り出して使います。

~~~
$rule['GET'] = [
  'id' => [
    'type'=>PuzzleBall::INTEGER,
    'range'=>['min'=>0, 'max'=>100],
  ]
];
$ball = new PuzzleBall($rule);
var_dump($ball->id);
~~~

これで、$ball->idには、0～100の整数のみが入ることになります。

ボールを作るにはルールが必要ですので、値の仕様を設計の段階であらかじめ明確にしておく必要があります。実装したルールには、設計上の仕様がそのまま反映されることになります。　　

また、 "http://～.php?id=10" というリクエストが行われた際は、
$_GET['id'] には '10' という文字列(string)が入りますが、$ball->id からは、10というint型（整数型）が得られます。これは、ルールに「整数を受け取る」と明示していることから、ボール内でint型に変換しているためです。コードでは、$ball->idの値は厳密にint型として扱ってよいです。

あと、一部のレンタルサーバにおけるCLI環境にも対応しています。
CLI環境では、$_GET, $_POST, $_COOKIEに値が自動的に代入されませんが、$_GETなどが空の場合は、クエリー文字列と標準入力と環境変数から値を受け取るようにしています。

# 使い方の大雑把な説明

## 1. requireする

~~~
require_once 'PuzzleBall.php';
~~~

この時点で、$_GET, $_POST, $_ENV, $_COOKIE は未定義状態になっています。

## 2. ルールを作る

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

## 3. ボールを作る

    $ball = new PuzzleBall($rule);

ルールを基にボールを作り、同時にGETパラメータやクッキーなどを受け取ります。  
もしルール外の値が渡されていた場合は、処理を中断して例外(BallException)を投げます。  

つまりPuzzleBallは、ユーザーに入力にさせた値に対して、サーバ側に変な値を送りつけないように、ブラウザ側で送信前にユーザーの入力値をチェックしていることを期待しています。HTML5のフォームの機能を使って制限したり、JavaScriptでチェックするなど、行ってください。  
こうすることで、サーバーに送信される値は、Webアプリケーションが作った値、もしくはユーザーが入力したものをブラウザ側でチェックしているもの、のどちらかになります。このため、悪意のないアクセスであれば、例外を投げることはありません。例外を投げるのは、悪意のあるアクセスか、機械的なアクセスか、Webアプリケーションのバグのみになるはずです。

## 4. 値をボールから取り出す

$_GET や $_POST の代わりに、

    $ball->page

$_COOKIE の代わりに、

    $ball->cookie('page')

$_ENV の代わりに、

    $ball->env('HTTP_USER_AGENT')

# ボールの作り方

```
$ball = new PuzzleBall($rule, $config);
```

$rule : ルール（後述）
$config : コンフィグ（後述）

## ルールの書き方

```
$rule['ソース'] = array(
	'パラメータ名' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
	'パラメータ名' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
    ....
);
```
または
```
$rule = array(
    'ソース' => array(
	    'パラメータ名' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
	    'パラメータ名' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
        ....
    ),
);
```
など。

配列の場合は、
```
$rule['ソース'] = array(
	'パラメータ名[]' = array(
		'index' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
		'value' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
	),
);
```
または、"a[]=1&a[]=2&a[]=3&..." という渡され方をする場合は、indexについては個数でも指定できます。 
```
$rule['ソース'] = array(
	'パラメータ名[]' = array(
		'index' => array( 'maxCount'=>個数の上限 ),
		'value' => array( 'type'=>タイプ, ['length'=>長さ指定,] ['range'=>範囲指定] ),
	),
);
```


|名前|説明|
|----|----|
|ソース|GET, POST, ENV, COOKIE|
|パラメータ名|その名の通り、パラメータ名。ids[]のように、[]を付けると、配列を求めるとして扱います。|
|タイプ|次の項目で説明|
|長さ指定|array( 'min'=>最小値, 'max'=>最大値)|
|範囲指定|array( 'min'=>最小値, 'max'=>最大値)|

おすすめの書き方は、
```
$pattern['name'] = array('type'=>PuzzleBall::UTF8, 'length'=>array('min'=>1, 'max'=>20);
$rule['POST'] = array(
    'myname' => $pattern['name'],
    'teacher_name' => $pattern['name'],
    'friends_name[]' => $pattern['name'],
);
```
のように、パラメータ名とは別にルールを定義する感じ（同じパラメータ名なら、どこで受け取っても同じルールを適用するでしょ？）。

配列を受け取る場合は、以下のように書きます。

例）a[]=1&a[]=2&a[]=3&b[1]=4&b[10]=5&c[key]=6&c[name]=7
```
$rule_val = array( 'type'=>PuzzleBall::INTEGER, 'range'=>array('min'=>1, 'max'=>10) );
$rules['GET'] = array(
	'a[]' => array(
		'maxCount' => 3,
		'value' => $rule_val,
	),
	'b[]' => array(
		'index' => array( 'type'=>PuzzleBall::INTEGER, 'range'=>array('min'=>0, 'max'=>10) ),
		'value' => $rule_val,
	),
	'c[]' => array(
		'index' => array( 'type'=>PuzzleBall::WHITELIST, 'list'=>array('key','name') ),
		'value' => $rule_val,
	),
);
```

## タイプ一覧

受け取るパラメータそれぞれに対し、どのような値を許容するかを指定します。    
* 文字種(type)
* 条件
 * 文字列の場合は、許容する文字列長(length)、
 * 数字の場合は、許容する値の範囲(range)、または許容する桁数(length)

rangeとlengthの両方を指定している場合は、rangeの指定のみをチェックします。   

|type|説明|数値型への変換|length指定|range指定|list指定|
|----|----|--------------|----------|---------|--------|
|PuzzleBall::PATTERN|任意の正規表現によるチェック|-|-|-|-|
|PuzzleBall::ALPHABET|アルファベット(大文字小文字)のみ|-|○|-|-|
|PuzzleBall::INTEGER|10進数の整数のみ|**○**|○|○|-|
|PuzzleBall::NUMBER|10進数の数字(と+記号、-記号、小数点)のみ|**○**|○|○|-|
|PuzzleBall::DIGIT|10進数の数字(と+記号、-記号、小数点)のみ|-|○|-|-|
|PuzzleBall::ALPHADIGIT|上記アルファベットと数字のみ|-|○|-|-|
|PuzzleBall::WHITELIST|許容する値を列挙したリスト(ホワイトリスト)に含まれる値のみ|-|-|-|○|
|PuzzleBall::UTF8|UTF8の文字列(改行文字含まず)のみ|-|○|-|-|
|PuzzleBall::UTF8TEXT|UTF8の文字列(改行文字を許容)のみ|-|○|-|-|

INTEGERの場合は、ボールからは、int型の値が得られます。  
NUMBERの場合は、小数点があればfloat型、なければint型の値が得られます。  
NUMBER と DIGIT の違いは、数値型への変換の有無のみです。  

# ルールに合わない値が送信されたら

new の時点で、PuzzleBall::BallException という例外を投げます。  
例外投げてほしくないときは、ONGOINGをコンフィグで設定します（後述）。

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
|fallback|任意の値|behaviorとして処理続行(ONGOING)を設定した場合に有効。ルールに合わない値が送信されたときの、代替値。指定がないときは、NULL。|

配列のときのONGOINGは、ちょっと特殊です。

ケース1)indexにtypeを定義しているとき

|パターン|EXCEPTION指定時|ONGIONG指定時|
|----|----|----|
|不正な値が来たら|例外投げる|代替値を代入する|
|不正なkeyが来たら|例外投げる|その要素を破棄する|
|不正なkeyと値が来たら|例外投げる|その要素を破棄する|

ケース2) indexにmaxCountを定義しているとき

|パターン|EXCEPTION指定時|ONGIONG指定時|
|----|----|----|
|不正な値が来たら|例外投げる|その要素を破棄する|
|個数の上限を超えたら|例外投げる|その要素を破棄する|


# コンフィグ以外の設定

ほとんどの場合で設定する必要はないと思いますが、require前に定数を定義することで、PuzzleBallの動作に関する一部設定を変えることができます。

|項目|設定値|説明|
|----|------|----|
|PUZZLEBALL_CLI_POST_MAX|数値|$_POSTの値を、標準入力から受け取る時の、最大文字数。指定のない時は、10240。|

例
~~~
define('PUZZLEBALL_CLI_POST_MAX', 20480);
require_once 'PuzzleBall.php';
~~~

# そのほか

$_INPUTは、対応予定ありません。

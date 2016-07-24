# PuzzleBall
PuzzleBall is a PHP library that check incoming variables.

# �T�v

����́A�o���f�[�V�����i���͒l���؁j�Ƃ͈قȂ�A�v���[�`�ŁAWeb�A�v���P�[�V���������҂����p�����[�^�l�݂̂��󂯎�邱�Ƃ�ۏႷ��A���C�u�����ł��B  

�c�����ɗV�񂾁i��������Ȃ��j�m��ߋ�A�ۂ�O�p�Ȃǂ̌����J�����{�[���i�ȉ��A�p�Y���{�[���j���v���o���Ă݂Ă��������B���̃p�Y���{�[���ɂ́A�ۂ�O�p�̌`�������s�[�X�����邱�Ƃ��ł��܂��B�ۂ��s�[�X�́A�O�p�`�̌��ɂ͓���܂���B�ۂ��s�[�X�͊ۂ����ɁA�O�p�̃s�[�X�͎O�p�`�̌��ɁA�l�p���s�[�X�͎l�p�`�̌��ɂ����A����܂���B�~�j�J�[�̂�����������悤�Ƃ��Ă��A�{�[���ɂ͂��̂悤�Ȍ����J���Ă��Ȃ��̂ŁA����邱�Ƃ͂ł��܂���B�܂�A�{�[���̒��ɂ́A�{�[���̎����ɓK�����`�̃s�[�X�݂̂������Ă��邱�ƂɂȂ�܂��B

����Ɠ������Ƃ��AWeb�A�v���P�[�V�����ł��s�����Ƃ������݂ł��B

# �Љ�

Web�A�v���P�[�V�����ł́A��ʂ�
> * �l���󂯎��  
��
* �o���f�[�V�����i���͒l���؁j����  
��
* ��������

�Ƃ������ɏ������s���܂��B�o���f�[�V�����́A���S�Ŗ��̋N����Ȃ�Web�A�v���P�[�V�������J�������ŕK�{�̏����ł��B�������A�������ɘR�ꂪ��������A��������������ӂ����肵�₷�������ł�����܂��B���������p�����[�^�̒�`�������܂��Ƃ����ꍇ���炠��܂��B  
�o���f�[�V�������s�\���ł����Ă�����Ȓl���󂯎�������͐���ɓ��삵�Ă��܂��܂��B���̂��Ƃ���A�u�o���f�[�V�����������������ׂ��v�Ƃ����̂́A�������� **�u����@�v** �ɗ��܂��Ă���̂�����ł͂Ȃ����Ɗ����Ă��܂��B   

�����ŁA����@�ł͂Ȃ��A���͒l���؂����Ȃ��ƁA�A�v���P�[�V�����͎󂯎��Ȃ��悤�ɂ��܂��B  
���̃��C�u�����́Arequire�������_�ŁA$_GET, $_POST, $_COOKIE, $_ENV��unset�i����`��ԂɁj���܂��B�܂�A
~~~
require_once 'PuzzleBall.php';
var_dump($_GET);
~~~
�����s����ƁA

> Notice: Undefined variable: _GET in ***.php  

�ƂȂ�܂��B����ł́A�A�v���P�[�V�����́A�����l���󂯎��܂���ˁB

�����ŁA�u���̂悤�Ȓl�ł���Ύ󂯎��v�Ƃ������[�����`���APuzzleBall���쐬���܂��B�p�Y���{�[���Ɍ���������悤�ɁB�����āA�{�[���̒��ɓ����Ă���p�[�c�����o���Ďg���܂��B

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

����ŁA$ball->id�ɂ́A0�`100�̐����݂̂����邱�ƂɂȂ�܂��B

�{�[�������ɂ̓��[�����K�v�ł��̂ŁA�l�̎d�l��݌v�̒i�K�ł��炩���ߖ��m�ɂ��Ă����K�v������܂��B�����������[���ɂ́A�݌v��̎d�l�����̂܂ܔ��f����邱�ƂɂȂ�܂��B�@�@

�܂��A "http://�`.php?id=10" �Ƃ������N�G�X�g���s��ꂽ�ۂ́A
$_GET['id'] �ɂ� '10' �Ƃ���������(string)������܂����A$ball->id ����́A10�Ƃ�������(int)�������܂��B����́A���[���Ɂu�������󂯎��v�Ɩ������Ă��邱�Ƃ���A�{�[�����Ő����ɕϊ����Ă��邽�߂ł��B�R�[�h�ł́A$ball->id�̒l�͌����ɐ����Ƃ��Ĉ����Ă悢�ł��B


# �g�����̑�G�c�Ȑ���

## 1. require����

~~~
require_once 'PuzzleBall.php';
~~~

���̎��_�ŁA$_GET, $_POST, $_ENV, $_COOKIE �͖���`��ԂɂȂ��Ă��܂��B

## 2. ���[�������

    $rule['GET'] = array(
      'page' => array(
        'type' => PuzzleBall::NUMBER,
        'range' => array('min'=>1, 'max'=>20)
      ),
    );

PHP5.4�ȍ~�Ȃ�

    $rule['GET'] = [
      'page' => [
        'type' => PuzzleBall::NUMBER,
        'range' => ['min'=>1, 'max'=>20]
      ]
    ];

## 3. �{�[�������

    $ball = new PuzzleBall($rule);

���[������Ƀ{�[�������A������GET�p�����[�^��N�b�L�[�Ȃǂ��󂯎��܂��B  
�������[���O�̒l���n����Ă����ꍇ�́A�����𒆒f���ė�O(BallException)�𓊂��܂��B  

�܂�PuzzleBall�́A���[�U�[�ɓ��͂ɂ������l�ɑ΂��āA�T�[�o���ɕςȒl�𑗂���Ȃ��悤�ɁA�u���E�U���ő��M�O�Ƀ��[�U�[�̓��͒l���`�F�b�N���Ă��邱�Ƃ����҂��Ă��܂��BHTML5�̃t�H�[���̋@�\���g���Đ���������AJavaScript�Ń`�F�b�N����ȂǁA�s���Ă��������B  
�������邱�ƂŁA�T�[�o�[�ɑ��M�����l�́AWeb�A�v���P�[�V������������l�A�������̓��[�U�[�����͂������̂��u���E�U���Ń`�F�b�N���Ă�����́A�̂ǂ��炩�ɂȂ�܂��B���̂��߁A���ӂ̂Ȃ��A�N�Z�X�ł���΁A��O�𓊂��邱�Ƃ͂���܂���B��O�𓊂���̂́A���ӂ̂���A�N�Z�X���A�@�B�I�ȃA�N�Z�X���AWeb�A�v���P�[�V�����̃o�O�݂̂ɂȂ�͂��ł��B

## 4. �l���{�[��������o��

$_GET �� $_POST �̑���ɁA

    $ball->page

$_COOKIE �̑���ɁA

    $ball->cookie('page')

$_ENV �̑���ɁA

    $ball->env('HTTP_USER_AGENT')

# �{�[���̍���

```
$ball = new PuzzleBall($rule, $config);
```

$rule : ���[���i��q�j
$config : �R���t�B�O�i��q�j

## ���[���̏�����

```
$rule['�\�[�X'] = array(
	'�p�����[�^��' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
	'�p�����[�^��' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
    ....
);
```
�܂���
```
$rule = array(
    '�\�[�X' => array(
	    '�p�����[�^��' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
	    '�p�����[�^��' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
        ....
    ),
);
```
�ȂǁB

�z��̏ꍇ�́A
```
$rule['�\�[�X'] = array(
	'�p�����[�^��[]' = array(
		'index' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
		'value' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
	),
);
```
�܂��́A"a[]=1&a[]=2&a[]=3&..." �Ƃ����n�����������ꍇ�́Aindex�ɂ��Ă͌��ł��w��ł��܂��B 
```
$rule['�\�[�X'] = array(
	'�p�����[�^��[]' = array(
		'index' => array( 'maxCount'=>���̏�� ),
		'value' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
	),
);
```


|���O|����|
|----|----|
|�\�[�X|GET, POST, ENV, COOKIE|
|�p�����[�^��|���̖��̒ʂ�A�p�����[�^���Bids[]�̂悤�ɁA[]��t����ƁA�z������߂�Ƃ��Ĉ����܂��B|
|�^�C�v|���̍��ڂŐ���|
|�����w��|array( 'min'=>�ŏ��l, 'max'=>�ő�l)|
|�͈͎w��|array( 'min'=>�ŏ��l, 'max'=>�ő�l)|

�������߂̏������́A
```
$pattern['name'] = array('type'=>PuzzleBall::UTF8, 'length'=>array('min'=>1, 'max'=>20);
$rule['POST'] = array(
    'myname' => $pattern['name'],
    'teacher_name' => $pattern['name'],
    'friends_name[]' => $pattern['name'],
);
```
�̂悤�ɁA�p�����[�^���Ƃ͕ʂɃ��[�����`���銴���i�����p�����[�^���Ȃ�A�ǂ��Ŏ󂯎���Ă��������[����K�p����ł���H�j�B

�z����󂯎��ꍇ�́A�ȉ��̂悤�ɏ����܂��B

��ja[]=1&a[]=2&a[]=3&b[1]=4&b[10]=5&c[key]=6&c[name]=7
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

## �^�C�v�ꗗ

�󂯎��p�����[�^���ꂼ��ɑ΂��A�ǂ̂悤�Ȓl�����e���邩���w�肵�܂��B    
* ������(type)
* ����
 * ������̏ꍇ�́A���e���镶����(length)�A
 * �����̏ꍇ�́A���e����l�͈̔�(range)�A�܂��͋��e���錅��(length)

range��length�̗������w�肵�Ă���ꍇ�́Arange�̎w��݂̂��`�F�b�N���܂��B   

|type|����|���l�^�ւ̕ϊ�|length�w��|range�w��|list�w��|
|----|----|----------|---------|--------|
|PuzzleBall::PATTERN|�C�ӂ̐��K�\���ɂ��`�F�b�N|-|-|-|-|
|PuzzleBall::ALPHABET|�A���t�@�x�b�g(�啶��������)�̂�|-|��|-|-|
|PuzzleBall::INTEGER|10�i���̐����̂�|**��**|��|��|-|
|PuzzleBall::NUMBER|10�i���̐���(��+�L���A-�L���A�����_)�̂�|**��**|��|��|-|
|PuzzleBall::DIGIT|10�i���̐���(��+�L���A-�L���A�����_)�̂�|-|��|-|-|
|PuzzleBall::ALPHADIGIT|��L�A���t�@�x�b�g�Ɛ����̂�|-|��|-|-|
|PuzzleBall::WHITELIST|���e����l��񋓂������X�g(�z���C�g���X�g)�Ɋ܂܂��l�̂�|-|-|-|��|
|PuzzleBall::UTF8|UTF8�̕�����(���s�����܂܂�)�̂�|-|��|-|-|
|PuzzleBall::UTF8TEXT|UTF8�̕�����(���s���������e)�̂�|-|��|-|-|

INTEGER�̏ꍇ�́A�{�[������́Aint�^�̒l�������܂��B  
NUMBER�̏ꍇ�́A�����_�������float�^�A�Ȃ����int�^�̒l�������܂��B  
NUMBER �� DIGIT �̈Ⴂ�́A���l�^�ւ̕ϊ��̗L���݂̂ł��B  

# ���[���ɍ���Ȃ��l�����M���ꂽ��

new �̎��_�ŁAPuzzleBall::BallException �Ƃ�����O�𓊂��܂��B
��O�����Ăق����Ȃ��Ƃ��́AONGOING���R���t�B�O�Őݒ肵�܂��i��q�j�B

�z��̂Ƃ��́A  

�P�[�X1)index��type���`���Ă���Ƃ�

|�p�^�[��|EXCEPTION�w�莞|ONGIONG�w�莞|
|----|----|----|
|�s���Ȓl��������|��O������|��֒l��������|
|�s����key��������|��O������|���̗v�f��j������|
|�s����key�ƒl��������|��O������|���̗v�f��j������|

�P�[�X2) index��maxCount���`���Ă���Ƃ�

|�p�^�[��|EXCEPTION�w�莞|ONGIONG�w�莞|
|----|----|----|
|�s���Ȓl��������|��O������|���̗v�f��j������|
|���̏���𒴂�����|��O������|���̗v�f��j������|

# �R���t�B�O�̏�����

```
$config = array(
    '����' => �ݒ�l,
);
```

|����|�ݒ�l|����|
|----|------|----|
|behavior|PuzzleBall::EXCEPTION|��O�𓊂���(�K�蓮��)|
||PuzzleBall::ONGOING|�������s|
|fallback|�C�ӂ̒l|behavior�Ƃ��ď������s(ONGOING)��ݒ肵���ꍇ�ɗL���B���[���ɍ���Ȃ��l�����M���ꂽ�Ƃ��́A��֒l�B�w�肪�Ȃ��Ƃ��́ANULL�B|

# �R���t�B�O�ȊO�̐ݒ�

�قƂ�ǂ̏ꍇ�Őݒ肷��K�v�͂Ȃ��Ǝv���܂����Arequire�O�ɒ萔���`���邱�ƂŁAPuzzleBall�̓���Ɋւ���ꕔ�ݒ��ς��邱�Ƃ��ł��܂��B

|����|�ݒ�l|����|
|----|------|----|
|PUZZLEBALL_CLI_POST_MAX|���l|$_POST�̒l���A�W�����͂���󂯎�鎞�́A�ő啶�����B�w��̂Ȃ����́A10240�B|

��
~~~
define('PUZZLEBALL_CLI_POST_MAX', 20480);
require_once 'PuzzleBall.php';
~~~

# ���̂ق�

$_INPUT�́A�Ή��\�肠��܂���B

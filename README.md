# puzzleball
PuzzleBall is a PHP library that check incoming variables.

# �T�v



# �C���X�g�[��

# �g�����̑�G�c�Ȑ���

## 1. ���[�������

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

## 2. �{�[�������

    $ball = new PuzzleBall($rule);

���[�������ƂɃ{�[�������A������GET�p�����[�^��N�b�L�[�Ȃǂ��󂯎��܂��B  
�������[���O�̒l���n���ꂽ�ꍇ�́A��O(BallException)�𓊂��܂��B  
�܂胆�[�U�[�ɓ��͂ɂ������l�𑗐M����ۂ́A�ςȒl�𑗂���Ȃ��悤�ɁAHTML5�̃t�H�[���̋@�\���g������AJavaScript�Ń`�F�b�N����Ȃǂ́A���������Ă������Ƃ����҂��Ă��܂��B  

## 3. �l���{�[��������o��

    $ball->page

## 4. $_GET, $_POST, $_COOKIE�͎g���Ȃ�

$_GET �́ANULL�ɂȂ�܂��B  
�O����̒l�́A�K���{�[��������o���Ă��������B  

# �{�[���̍���

```
$ball = new PuzzleBall($rule, $config);
```

$rule : ���[���i��q�j
$config : �R���t�B�O�i��q�j

# ���[���̏�������

```
$rule['���'] = array(
	'�p�����[�^��' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
	'�p�����[�^��' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
    ....
);
```
�܂���
```
$rule = array(
    '���' => array(
	    '�p�����[�^��' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
	    '�p�����[�^��' => array( 'type'=>�^�C�v, ['length'=>�����w��,] ['range'=>�͈͎w��] ),
        ....
    ),
);
```
�ȂǁB

|���O|����|
|----|----|
|���|GET, POST, ENV, COOKIE|
|�p�����[�^��|���̖��̒ʂ�A�p�����[�^���Bids[]�̂悤�ɁA[]��t����ƁA�z������߂�Ƃ��Ĉ����܂��B|
|�^�C�v|���̍��ڂŐ���|
|�����w��|array( 'min'=>�ŏ��l, 'max'=>�ő�l)�@�ȗ��B|
|�͈͎w��|array( 'min'=>�ŏ��l, 'max'=>�ő�l)�@�ȗ��B|

�������߂̏������́A
```
$pattern['name'] = array('type'=>PuzzleBall::UTF8, 'length'=>array('min'=>1, 'max'=>20);
$rule['POST'] = array(
    'myname' => $pattern['name'],
    'teacher_name' => $pattern['name'],
    'friends_name[]' => $pattern['name'],
);
```
�̂悤�ɁA�p�����[�^���Ƃ͕ʌɃ��[�����`���銴���i���O�Ȃ�A����̖��O�ł����Ă��������[����K�p����ł���j�B

# �^�C�v�ꗗ

�󂯎��p�����[�^���ꂼ��ɑ΂��A�ǂ̂悤�Ȓl�����e���邩���w�肵�܂��B    
* ������(type)
* ������̏ꍇ�́A���e���镶����(length)�A
�@�����̏ꍇ�́A���e����l�͈̔�(range)�A�܂��͋��e���錅��(length)

range��length�̗������w�肵�Ă���ꍇ�́Arange�̎w��݂̂��`�F�b�N���܂��B   
range��length�̂ǂ�����w�肵�Ă��Ȃ��ꍇ�́Alength�Ƃ��ĕ������16�����A������3�����w�肳�ꂽ���̂Ƃ��Ĉ����܂��B   

|type|����|length�w��|range�w��|
|----|----|----------|---------|
|PuzzleBall::PATTERN|�C�ӂ̐��K�\���ɂ��`�F�b�N|�Ȃ�|�Ȃ�|
|PuzzleBall::INTEGER|�����̂�|����|����|
|PuzzleBall::ALPHABET|�A���t�@�x�b�g(�啶��������)�̂�|����|�Ȃ�|
|PuzzleBall::NUMBER|����(��+�L���A-�L���A�����_)�̂�|����|����|
|PuzzleBall::ALPHANUM|��L�A���t�@�x�b�g�Ɛ����̂�|����|�Ȃ�|
|PuzzleBall::WHITELIST|���e����l��񋓂������X�g(�z���C�g���X�g)�Ɋ܂܂��l�̂�|�Ȃ�|�Ȃ�|
|PuzzleBall::UTF8|UTF8�̕�����(���s�����܂܂�)�̂�|����|�Ȃ�|
|PuzzleBall::UTF8TEXT|UTF8�̕�����(���s���������e)�̂�|����|�Ȃ�|

# ���[���ɍ���Ȃ��l�����M���ꂽ��

new �̎��_�ŁAPuzzleBall::BallException �Ƃ�����O�𓊂��܂��B    
��O�����Ăق����Ȃ��Ƃ��́A�R���t�B�O�Őݒ肵�܂��i��q�j�B

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
|fallback|behavior�Ƃ��ď������s��ݒ肵���ꍇ�ɗL���B���[���ɍ���Ȃ��l�����M���ꂽ�Ƃ��́A��֒l||

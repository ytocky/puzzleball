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

# �^�C�v�ꗗ

|PATTERN||
|INTEGER||
|ALPHABET||
|NUMBER||
|ALPHANUM||
|WHITELIST||
|UTF8||
|UTF8TEXT||

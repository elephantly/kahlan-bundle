<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 27/02/17
 * Time: 16:43
 */
class Stubs
{
    /**
     * Frequency type All for before and after
     */
    const FREQUENCY_ALL  = 'All';
    /**
     * Frequency type Each for before and after
     */
    const FREQUENCY_EACH = 'Each';

    const STUB_CLASS     = 'Class';

    const STUB_METHOD    = 'Method';

    const TIMING_BEFORE  = 'before';

    const TIMING_AFTER   = 'after';

    /**
     * @var array
     */
    public static $tokens = array(
        'classContext',
        'className',
        'classContent',
        'beforeClass',
        'dependenciesBlock',
        'methodContext',
        'methodSpecs',
        'itContext',
        'beforeMethod',
        'patching',
        'methodContent',
        'methodName',
        'methodArgs',
        'expectCondition',
        'afterMethod',
        'afterClass',
        'resetting',
        'finally',
        'frequency',
    );

    /**
     * @var string
     */
    public static $classContext = <<<'EOD'
<?php
/**
 * Spec stub created using KahlanBundle
 * (c) Tiriel @ Elephantly
 * Kahlan created by Jails @ Kahlan
 */
 
describe(%className%, function () {
    %classContent%
});
EOD;

    /**
     * @var string
     */
    private static $beforeClass = <<<'EOD'
    before%frequency%(function () {
        %dependeciesBlock%
    });
EOD;

    /**
     * @var string
     */
    public static $testedService = <<<'EOD'
        $this->%className% = $this->get('
EOD;

    /**
     * @var string
     */
    public static $methodContext = <<<'EOD'
    describe(%methodName%, function () {
        %methodSpecs%
    });
EOD;

    /**
     * @var string
     */
    private static $beforeMethod =  <<<'EOD'
        before%frequency%(function () {
            %patching%
        });
EOD;

    /**
     * @var string
     */
    public static $itContext = <<<'EOD'
        it('expects return to be %returnType%', function () {
            %methodContent%
            %expectCondition%
        });
EOD;

    /**
     * @var string
     */
    public static $methodContent = <<<'EOD'
            $result = %className%->%methodName%(%methodArgs%);
EOD;

    /**
     * @var string
     */
    public static $expectCondition = <<<'EOD'
            expect($result)->%expectCondition%;
EOD;

    /**
     * @var string
     */
    private static $afterMethod =  <<<'EOD'
        after%frequency%(function () {
            %resetting%
        });
EOD;

    /**
     * @var string
     */
    private static $afterClass = <<<'EOD'
    after%frequency%(function () {
        %finally%
    });
EOD;

    /**
     * @param $timing
     * @param $type
     * @param $freq
     * @return mixed
     */
    private static function stubbing($timing, $type, $freq)
    {
        $type   = ucfirst($type);
        $freq   = ucfirst($freq);
        if (($typeErr = !in_array($type, array(self::STUB_CLASS, self::STUB_METHOD))) || !in_array($freq, array(self::FREQUENCY_ALL, self::FREQUENCY_EACH))) {
            $error = isset($typeErr) ? array('type', self::STUB_CLASS, self::STUB_METHOD) : array('frequency', self::FREQUENCY_ALL, self::FREQUENCY_EACH) ;
            throw new InvalidArgumentException("Stubbing $error[0] must be either '$error[1]' or '$error[2]'");
        }
        $static = "$timing$type";

        return str_replace('%frequency%', $freq, self::$$static);
    }

    /**
     * @param $type
     * @param $freq
     * @return mixed
     */
    public static function after($type, $freq)
    {
        return self::stubbing(self::TIMING_AFTER, $type, $freq);
    }

    /**
     * @param $type
     * @param $freq
     * @return mixed
     */
    public static function before($type, $freq)
    {
        return self::stubbing(self::TIMING_BEFORE, $type, $freq);
    }
}
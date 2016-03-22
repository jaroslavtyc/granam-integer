<?php
namespace Granam\Tests\Integer;

use Granam\Integer\IntegerObject;

class IntegerObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideStrictnessAndParanoia
     * @param bool $strict
     * @param bool $paranoid
     */
    public function I_can_use_it($strict, $paranoid)
    {
        $integerObject = new IntegerObject($value = 12345, $strict, $paranoid);
        self::assertNotNull($integerObject);
        self::assertInstanceOf('Granam\Integer\IntegerInterface', $integerObject);
        self::assertSame($value, $integerObject->getValue());
        self::assertSame((string)$value, (string)$integerObject);
    }

    public function provideStrictnessAndParanoia()
    {
        return [
            [false, false],
            [true, false],
            [false, true],
            [true, true],
        ];
    }

    /**
     * @test
     * @dataProvider provideStrictnessAndParanoia
     * @param bool $strict
     * @param bool $paranoid
     */
    public function I_can_get_value_from_non_native_integer($strict, $paranoid)
    {
        $fromString = new IntegerObject($stringValue = '123456', $strict, $paranoid);
        self::assertSame((int)$stringValue, $fromString->getValue());
        $fromFloat = new IntegerObject($floatValue = 1.0, $strict, $paranoid);
        self::assertSame(1, $fromFloat->getValue());
        self::assertSame((int)$floatValue, $fromFloat->getValue());
        $fromFalse = new IntegerObject(false, $strict, $paranoid);
        self::assertSame(0, $fromFalse->getValue());
        self::assertSame((int)false, $fromFalse->getValue());
        $fromTrue = new IntegerObject(true, $strict, $paranoid);
        self::assertSame(1, $fromTrue->getValue());
        self::assertSame((int)true, $fromTrue->getValue());
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Exceptions\WrongParameterType
     * @dataProvider provideStrictnessAndParanoia
     * @param bool $strict
     * @param bool $paranoid
     */
    public function I_cannot_use_float_with_decimal($strict, $paranoid)
    {
        new IntegerObject(1.1, $strict, $paranoid);
    }

    /**
     * @test
     * @dataProvider provideParanoia
     * @param bool $paranoid
     */
    public function I_can_use_empty_values_as_integer_zero_if_not_strict($paranoid)
    {
        $fromNull = new IntegerObject(null, false /* not strict */, $paranoid);
        self::assertSame(0, $fromNull->getValue());
        self::assertSame((int)null, $fromNull->getValue());
        $fromEmptyString = new IntegerObject('', false /* not strict */, $paranoid);
        self::assertSame(0, $fromEmptyString->getValue());
        self::assertSame((int)'', $fromEmptyString->getValue());
    }

    public function provideParanoia()
    {
        return [
            [true],
            [false]
        ];
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @dataProvider provideEmptyValue
     * @param $value
     */
    public function I_can_not_use_empty_value_by_default($value)
    {
        new IntegerObject($value);
    }

    public function provideEmptyValue()
    {
        return [
            [''],
            [null],
            [" \t\n  \r "]
        ];
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Exceptions\WrongParameterType
     * @dataProvider provideStrictnessAndParanoia
     * @param bool $strict
     * @param bool $paranoid
     */
    public function I_cannot_use_array($strict, $paranoid)
    {
        new IntegerObject([], $strict, $paranoid);
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Exceptions\WrongParameterType
     * @dataProvider provideStrictnessAndParanoia
     * @param bool $strict
     * @param bool $paranoid
     */
    public function I_cannot_use_resource($strict, $paranoid)
    {
        new IntegerObject(tmpfile(), $strict, $paranoid);
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Exceptions\WrongParameterType
     * @dataProvider provideStrictnessAndParanoia
     * @param bool $strict
     * @param bool $paranoid
     */
    public function I_cannot_use_object_without_to_string($strict, $paranoid)
    {
        new IntegerObject(new \stdClass(), $strict, $paranoid);
    }

    /**
     * @test
     * @dataProvider provideStrictnessAndParanoia
     * @param bool $strict
     * @param bool $paranoid
     */
    public function I_can_use_to_string_object($strict, $paranoid)
    {
        $integer = new IntegerObject(new TestWithToString($integerValue = 12345), $strict, $paranoid);
        self::assertSame($integerValue, $integer->getValue());
        $stringInteger = new IntegerObject(new TestWithToString($stringValue = '98765'), $strict, $paranoid);
        self::assertSame((int)$stringValue, $stringInteger->getValue());
        $floatInteger = new IntegerObject(new TestWithToString($floatValue = 123.0), $strict, $paranoid);
        self::assertSame((int)$floatValue, $floatInteger->getValue());
        $stringFloatInteger = new IntegerObject(new TestWithToString($stringFloatValue = '987.0'), $strict, $paranoid);
        self::assertSame((int)$stringFloatValue, $stringFloatInteger->getValue());
    }

    /**
     * @test
     */
    public function I_can_use_to_string_object_without_number_as_integer_zero_if_not_strict()
    {
        $integer = new IntegerObject(new TestWithToString($string = 'non-integer'), false /* not strict */);
        self::assertSame(0, $integer->getValue());
        self::assertSame((int)$string, $integer->getValue());
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Exceptions\WrongParameterType
     */
    public function I_can_not_use_non_numeric_value_by_default()
    {
        new IntegerObject('one');
    }

    /**
     * @test
     * @dataProvider provideStrictnessAndParanoia
     * @param bool $strict
     * @param bool $paranoid
     */
    public function I_get_value_without_trash($strict, $paranoid)
    {
        $integer = new IntegerObject($withLeadingZeroes = '000123', $strict, $paranoid);
        self::assertSame(123, $integer->getValue());
        self::assertSame((int)$withLeadingZeroes, $integer->getValue());
        $integer = new IntegerObject($zeroesAround = '00012345.000', $strict, $paranoid);
        self::assertSame(12345, $integer->getValue());
        self::assertSame((int)$zeroesAround, $integer->getValue());
    }

    /**
     * @test
     */
    public function I_can_use_value_with_non_numeric_trash_if_not_strict()
    {
        $integer = new IntegerObject($wrappedWithTrash = '   000123   foo bar', false /* not strict */);
        self::assertSame(123, $integer->getValue());
        self::assertSame((int)$wrappedWithTrash, $integer->getValue());
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Tools\Exceptions\WrongParameterType
     */
    public function I_can_not_use_value_with_non_numeric_trash_by_default()
    {
        new IntegerObject('   000123   foo bar');
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Exceptions\WrongParameterType
     */
    public function I_cannot_use_string_number_greater_than_int_max()
    {
        new IntegerObject(PHP_INT_MAX . '123');
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @dataProvider provideStrictness
     * @param bool $strict
     */
    public function I_can_force_exception_throw_for_rounding($strict)
    {
        $value = '123.999999999999999999';
        try {
            $silentlyRounded = new IntegerObject($value);
            self::assertSame(124, $silentlyRounded->getValue());
        } catch (\Exception $exception) {
            self::fail('Unexpected exception: ' . $exception->getMessage());
        }

        new IntegerObject($value, $strict, true /* paranoid */);
    }

    public function provideStrictness()
    {
        return [
            [true],
            [false]
        ];
    }
}

/** inner */
class TestWithToString
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string)$this->value;
    }
}

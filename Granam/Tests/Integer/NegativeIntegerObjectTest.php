<?php
namespace Granam\Tests\Integer;

use Granam\Integer\NegativeInteger;
use Granam\Integer\NegativeIntegerObject;
use Granam\Number\NegativeNumber;

class NegativeIntegerObjectTest extends ICanUseItSameWayAsUsing
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $zeroNegativeInteger = new NegativeIntegerObject(0);
        self::assertSame(0, $zeroNegativeInteger->getValue());
        self::assertInstanceOf(NegativeInteger::class, $zeroNegativeInteger);
        self::assertInstanceOf(NegativeNumber::class, $zeroNegativeInteger);

        $negativeInteger = new NegativeIntegerObject(-1);
        self::assertSame(-1, $negativeInteger->getValue());
        self::assertInstanceOf(NegativeInteger::class, $negativeInteger);
        self::assertInstanceOf(NegativeNumber::class, $negativeInteger);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_zero()
    {
        $zeroNegativeInteger = new NegativeIntegerObject(0);
        self::assertSame(0, $zeroNegativeInteger->getValue());
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Tools\Exceptions\NegativeIntegerCanNotBePositive
     * @expectedExceptionMessageRegExp ~\s1~
     */
    public function I_can_not_create_it_positive()
    {
        new NegativeIntegerObject(1);
    }

    /**
     * @test
     */
    public function I_can_use_it_just_with_value_parameter()
    {
        $this->assertUsableWithJustValueParameter('\Granam\Integer\NegativeIntegerObject', '__construct');
    }

    /**
     * @test
     */
    public function I_can_create_it_same_way_as_to_negative_integer_conversion()
    {
        $this->I_can_create_it_same_way_as_using('toNegativeInteger', '\Granam\Integer\NegativeIntegerObject');
    }
}
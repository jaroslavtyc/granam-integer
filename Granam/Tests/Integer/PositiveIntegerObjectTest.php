<?php
namespace Granam\Tests\Integer;

use Granam\Integer\PositiveInteger;
use Granam\Integer\PositiveIntegerObject;
use Granam\Number\PositiveNumber;

class PositiveIntegerObjectTest extends ICanUseItSameWayAsUsing
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $positiveInteger = new PositiveIntegerObject(3);
        self::assertSame(3, $positiveInteger->getValue());
        self::assertInstanceOf(PositiveInteger::class, $positiveInteger);
        self::assertInstanceOf(PositiveNumber::class, $positiveInteger);
    }

    /**
     * @test
     */
    public function I_can_create_it_with_zero()
    {
        $zeroPositiveInteger = new PositiveIntegerObject(0);
        self::assertSame(0, $zeroPositiveInteger->getValue());
    }

    /**
     * @test
     * @expectedException \Granam\Integer\Tools\Exceptions\PositiveIntegerCanNotBeNegative
     * @expectedExceptionMessageRegExp ~\s-1~
     */
    public function I_can_not_create_it_negative()
    {
        new PositiveIntegerObject(-1);
    }

    /**
     * @test
     */
    public function I_can_use_it_just_with_value_parameter()
    {
        $this->assertUsableWithJustValueParameter('\Granam\Integer\PositiveIntegerObject', '__construct');
    }

    /**
     * @test
     */
    public function I_can_create_it_same_way_as_using_to_positive_integer_conversion()
    {
        $this->I_can_create_it_same_way_as_using('toPositiveInteger', '\Granam\Integer\PositiveIntegerObject');
    }
}
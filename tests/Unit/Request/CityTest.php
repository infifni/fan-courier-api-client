<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Tests\Unit\Request;

use Infifni\FanCourierApiClient\Exception\FanCourierInvalidParamException;
use Infifni\FanCourierApiClient\Request\City;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{
    private $params = [
        'judet' => 'Bihor',
        'language' => 'ro'
    ];

    public function testValidateSuccessfulWithNoParams()
    {
        $city = new City();
        $params = [];

        $this->assertTrue($city->validate($params), 'expected successful validation');
    }

    public function testValidateSuccessfulWithParams()
    {
        $city = new City();
        $params = $this->params;

        $this->assertTrue($city->validate($params), 'expected successful validation');
    }

    public function testFailedExtraParams()
    {
        $city = new City();
        $params = $this->params;
        $params['pot'] = 'yes';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches(
            "/^These keys are not allowed: /"
        );

        $city->validate($params);
    }

    public function testFailedNotAllowedParamValue()
    {
        $city = new City();
        $params = $this->params;
        $params['language'] = 'universal';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches(
            "/^Field 'language' can have one of the following values: /"
        );

        $city->validate($params);
    }
}
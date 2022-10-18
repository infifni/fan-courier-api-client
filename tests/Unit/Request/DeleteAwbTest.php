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
use Infifni\FanCourierApiClient\Request\DeleteAwb;
use PHPUnit\Framework\TestCase;

class DeleteAwbTest extends TestCase
{
    private $params = [
        'AWB' => 'testawb'
    ];

    public function testValidateSuccessful()
    {
        $deleteAwb = new DeleteAwb();
        $params = $this->params;

        $this->assertTrue($deleteAwb->validate($params), 'expected successful validation');
    }

    public function testFailedExtraParams()
    {
        $deleteAwb = new City();
        $params = $this->params;
        $params['yupi'] = 'lots of';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches(
            "/^These keys are not allowed: /"
        );

        $deleteAwb->validate($params);
    }

    public function testFailedMissingParams()
    {
        $deleteAwb = new DeleteAwb();
        $params = [];

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'AWB' is required");

        $deleteAwb->validate($params);
    }
}
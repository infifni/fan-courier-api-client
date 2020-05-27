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
use Infifni\FanCourierApiClient\Request\ExportServices;
use PHPUnit\Framework\TestCase;

class ExportServicesTest extends TestCase
{
    public function testValidateSuccessful()
    {
        $exportServices = new ExportServices();

        $this->assertTrue($exportServices->validate(), 'expected successful validation');
    }

    public function testValidateFailed()
    {
        $exportServices = new ExportServices();
        $params = [
            'extra' => 'not needed'
        ];

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage('No fields required');

        $exportServices->validate($params);
    }
}
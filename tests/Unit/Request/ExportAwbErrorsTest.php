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
use Infifni\FanCourierApiClient\Request\ExportAwbErrors;
use PHPUnit\Framework\TestCase;

class ExportAwbErrorsTest extends TestCase
{
    public function testValidateSuccessful()
    {
        $exportAwbErrors = new ExportAwbErrors();

        $this->assertTrue($exportAwbErrors->validate(), 'expected successful validation');
    }

    public function testValidateFailed()
    {
        $exportAwbErrors = new ExportAwbErrors();
        $params = [
            'extra' => 'not needed'
        ];

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage('No fields required');

        $exportAwbErrors->validate($params);
    }
}
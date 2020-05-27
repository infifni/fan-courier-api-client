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
use Infifni\FanCourierApiClient\Request\ExportObservations;
use PHPUnit\Framework\TestCase;

class ExportObservationsTest extends TestCase
{
    public function testValidateSuccessful()
    {
        $exportObservations = new ExportObservations();

        $this->assertTrue($exportObservations->validate(), 'expected successful validation');
    }

    public function testValidateFailed()
    {
        $exportObservations = new ExportObservations();
        $params = [
            'extra' => 'not needed'
        ];

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage('No fields required');

        $exportObservations->validate($params);
    }
}
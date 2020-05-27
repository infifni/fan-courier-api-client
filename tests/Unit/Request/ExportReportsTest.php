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
use Infifni\FanCourierApiClient\Request\ExportReports;
use PHPUnit\Framework\TestCase;

class ExportReportsTest extends TestCase
{
    private $params = [
        'data' => '16.05.2020',
        'language' => 'ro'
    ];

    public function testValidateSuccessfulOnlyRequiredParams()
    {
        $params = $this->params;
        unset($params['language']);
        $exportReports = new ExportReports();

        $this->assertTrue($exportReports->validate($params), 'expected successful validation');
    }

    public function testValidateSuccessfulAllParams()
    {
        $params = $this->params;
        $exportReports = new ExportReports();

        $this->assertTrue($exportReports->validate($params), 'expected successful validation');
    }

    public function testValidateFailedExtraParam()
    {
        $exportReports = new ExportReports();
        $params = $this->params;
        $params['detailed'] = 'so detailed';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^The only keys accepted are: /");

        $exportReports->validate($params);
    }

    public function testValidateFailedMissingRequiredParam()
    {
        $exportReports = new ExportReports();
        $params = $this->params;
        unset($params['data']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'data' is required");

        $exportReports->validate($params);
    }
}
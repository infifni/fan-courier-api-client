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
use Infifni\FanCourierApiClient\Request\ExportBordereau;
use PHPUnit\Framework\TestCase;

class ExportBordereauTest extends TestCase
{
    private $params = [
        'data' => '16.05.2020',
        'language' => 'ro',
        'mode' => ExportBordereau::MODE_ONLY_SELFAWB_ALLOWED_VALUE
    ];

    public function testValidateSuccessfulOnlyRequiredParams()
    {
        $params = $this->params;
        unset($params['language'], $params['mode']);
        $exportBordereau = new ExportBordereau();

        $this->assertTrue($exportBordereau->validate($params), 'expected successful validation');
    }

    public function testValidateSuccessfulAllParams()
    {
        $params = $this->params;
        $exportBordereau = new ExportBordereau();

        $this->assertTrue($exportBordereau->validate($params), 'expected successful validation');
    }

    public function testValidateFailedExtraParam()
    {
        $exportBordereau = new ExportBordereau();
        $params = $this->params;
        $params['detailed'] = 'so detailed';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^These keys are not allowed: /");

        $exportBordereau->validate($params);
    }

    public function testValidateFailedMissingRequiredParam()
    {
        $exportBordereau = new ExportBordereau();
        $params = $this->params;
        unset($params['data']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'data' is required");

        $exportBordereau->validate($params);
    }

    public function testValidateFailedNotAllowedValueForParam()
    {
        $exportBordereau = new ExportBordereau();
        $params = $this->params;
        $params['mode'] = 3;

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^Field 'mode' can have one of the following values:/");

        $exportBordereau->validate($params);
    }
}
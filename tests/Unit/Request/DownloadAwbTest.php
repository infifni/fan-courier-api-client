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
use Infifni\FanCourierApiClient\Request\DownloadAwb;
use PHPUnit\Framework\TestCase;

class DownloadAwbTest extends TestCase
{
    private $params = [
        'AWB' => 'testawb'
    ];

    public function testValidateSuccessfulOnlyRequiredParams()
    {
        $downloadAwb = new DownloadAwb();
        $params = $this->params;

        $this->assertTrue($downloadAwb->validate($params), 'expected successful validation');
    }

    public function testValidateSuccessfulAllParams()
    {
        $downloadAwb = new DownloadAwb();
        $params = $this->params;
        $params['language'] = 'ro';

        $this->assertTrue($downloadAwb->validate($params), 'expected successful validation');
    }

    public function testFailedExtraParams()
    {
        $downloadAwb = new DownloadAwb();
        $params = $this->params;
        $params['netflix'] = 'sure why not';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^The only keys accepted are: /");

        $downloadAwb->validate($params);
    }

    public function testFailedMissingParams()
    {
        $downloadAwb = new DownloadAwb();
        $params = [];

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'AWB' is required");

        $downloadAwb->validate($params);
    }
}
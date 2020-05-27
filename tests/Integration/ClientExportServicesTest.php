<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Tests\Integration;

use Infifni\FanCourierApiClient\Exception\FanCourierInvalidParamException;

class ClientExportServicesTest extends BaseTestCase
{
    public function testExportOrdersSuccessful()
    {
        $results = $this->client->exportServices();

        $this->assertIsArray($results);
        foreach ($results as $result) {
            $this->assertArrayHasKey('servicii_fan_courier', $result);
        }
    }

    public function testExportServicesFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage('No fields required');

        $this->client->exportServices([
            'extra' => 'top'
        ]);
    }
}
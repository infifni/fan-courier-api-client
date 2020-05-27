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

class ClientExportObservationsTest extends BaseTestCase
{
    public function testExportObservationsSuccessful()
    {
        $results = $this->client->exportObservations();

        foreach ($results as $result) {
            $this->assertIsArray($result);
            $this->assertArrayHasKey('observatii_fan_courier', $result);
            $this->assertIsString($result['observatii_fan_courier']);
        }
    }

    public function testExportAwbErrorsFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage('No fields required');

        $this->client->exportObservations([
            'Extra' => 'cheese'
        ]);
    }
}
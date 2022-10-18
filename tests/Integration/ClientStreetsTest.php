<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Tests\Integration;

use Exception;
use Infifni\FanCourierApiClient\Exception\FanCourierInvalidParamException;
use Infifni\FanCourierApiClient\Request\Streets;

class ClientStreetsTest extends BaseTestCase
{
    private $responseRoFields = [
        'judet',
        'localitate',
        'strada',
        'de_la',
        'pana_la',
        'paritate',
        'cod_postal',
        'tip',
        'cod_cartare',
        'numar_depozit',
        'id_strada',
        'cod_sortare_vizual',
        'litera_cartare',
        'agentie'
    ];

    private $responseEnFields = [
        'county',
        'city',
        'street',
        'from',
        'to',
        'parity',
        'zip_code',
        'type',
        'routing_code',
        'depot_number',
        'street_id',
        'visual_sorting_code',
        'routing_letter',
        'agency'
    ];

    /**
     * @throws Exception
     */
    public function testStreetsSuccessfulOnlyCountyParam()
    {
        $results = $this->client->streets([
            'judet' => 'Bihor'
        ]);

        $this->assertIsArray($results);
        $nrResults = count($results);
        $step = $nrResults / 10;
        for ($index = 0; $index < $nrResults;) {
            foreach ($this->responseRoFields as $field) {
                $this->assertArrayHasKey($field, $results[$index]);
            }

            $index += random_int(1, $step);
        }
    }

    /**
     * @throws Exception
     */
    public function testStreetsSuccessfulAllParams()
    {
        $results = $this->client->streets([
            'judet' => 'Bihor',
            'language' => Streets::LANGUAGE_EN_ALLOWED_VALUE,
            'localitate' => 'Oradea'
        ]);

        $this->assertIsArray($results);
        $nrResults = count($results);
        $step = $nrResults / 10;
        for ($index = 0; $index < $nrResults;) {
            foreach ($this->responseEnFields as $field) {
                $this->assertArrayHasKey($field, $results[$index]);
            }
            $this->assertEquals('Bihor', $results[$index]['county']);
            $this->assertEquals('Oradea', $results[$index]['city']);

            $index += random_int(1, $step);
        }
    }

    public function testStreetsFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches('/^These keys are not allowed: /');

        $this->client->streets([
            'judet' => 'Bihor',
            'language' => Streets::LANGUAGE_EN_ALLOWED_VALUE,
            'extra' => 'top'
        ]);
    }

    public function testStreetsFailedNotAllowedValueParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^Field 'language' can have one of the following values: /");

        $this->client->streets([
            'judet' => 'Bihor',
            'language' => 'au'
        ]);
    }
}
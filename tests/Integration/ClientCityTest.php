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

class ClientCityTest extends BaseTestCase
{
    /**
     * @var string[]
     */
    private $params = [
        'judet' => 'Bihor',
        'language' => 'en'
    ];

    /**
     * @throws Exception
     */
    public function testCitySuccessfulOnlyCountyParam()
    {
        $params = $this->params;
        unset($params['language']);
        $results = $this->client->city($params);
        $nrResults = count($results);
        for ($index = 0; $index < $nrResults;) {
            $this->assertContains($params['judet'], $results[$index]);
            $this->assertArrayHasKey('judet', $results[$index]);
            $this->assertArrayHasKey('localitate', $results[$index]);
            $this->assertArrayHasKey('agentie', $results[$index]);
            $this->assertArrayHasKey('km', $results[$index]);
            $this->assertArrayHasKey('cod_rutare', $results[$index]);
            $this->assertArrayHasKey('id_localitate_fan', $results[$index]);
            $this->assertArrayHasKey('litera_cartare', $results[$index]);

            $index += random_int(20, 50);
        }
    }

    /**
     * @throws Exception
     */
    public function testCitySuccessfulAllParams()
    {
        $params = $this->params;
        $results = $this->client->city($params);
        $nrResults = count($results);
        for ($index = 0; $index < $nrResults;) {
            $this->assertContains($params['judet'], $results[$index]);
            $this->assertArrayHasKey('county', $results[$index]);
            $this->assertArrayHasKey('city', $results[$index]);
            $this->assertArrayHasKey('fan_agency', $results[$index]);
            $this->assertArrayHasKey('external_km', $results[$index]);
            $this->assertArrayHasKey('routing_code', $results[$index]);
            $this->assertArrayHasKey('fan_city_id', $results[$index]);
            $this->assertArrayHasKey('routing_letter', $results[$index]);

            $index += random_int(20, 50);
        }
    }

    /**
     * @throws Exception
     */
    public function testCitySuccessfulNoParams()
    {
        $results = $this->client->city();
        $exportResultsAsString = var_export($results, true);

        $this->assertStringContainsString('Alba', $exportResultsAsString);
        $this->assertStringContainsString('Arad', $exportResultsAsString);
        $this->assertStringContainsString('Arges', $exportResultsAsString);
        $this->assertStringContainsString('Bacau', $exportResultsAsString);
        $this->assertStringContainsString('Bihor', $exportResultsAsString);
        $this->assertStringContainsString('Bistrita-Nasaud', $exportResultsAsString);
        $this->assertStringContainsString('Botosani', $exportResultsAsString);
        $this->assertStringContainsString('Brasov', $exportResultsAsString);
        $this->assertStringContainsString('Braila', $exportResultsAsString);
        $this->assertStringContainsString('Buzau', $exportResultsAsString);
        $this->assertStringContainsString('Caras-Severin', $exportResultsAsString);
        $this->assertStringContainsString('Cluj', $exportResultsAsString);
        $this->assertStringContainsString('Constanta', $exportResultsAsString);
        $this->assertStringContainsString('Covasna', $exportResultsAsString);
        $this->assertStringContainsString('Dambovita', $exportResultsAsString);
        $this->assertStringContainsString('Dolj', $exportResultsAsString);
        $this->assertStringContainsString('Galati', $exportResultsAsString);
        $this->assertStringContainsString('Gorj', $exportResultsAsString);
        $this->assertStringContainsString('Harghita', $exportResultsAsString);
        $this->assertStringContainsString('Hunedoara', $exportResultsAsString);
        $this->assertStringContainsString('Ialomita', $exportResultsAsString);
        $this->assertStringContainsString('Iasi', $exportResultsAsString);
        $this->assertStringContainsString('Ilfov', $exportResultsAsString);
        $this->assertStringContainsString('Maramures', $exportResultsAsString);
        $this->assertStringContainsString('Mehedinti', $exportResultsAsString);
        $this->assertStringContainsString('Mures', $exportResultsAsString);
        $this->assertStringContainsString('Neamt', $exportResultsAsString);
        $this->assertStringContainsString('Olt', $exportResultsAsString);
        $this->assertStringContainsString('Prahova', $exportResultsAsString);
        $this->assertStringContainsString('Satu Mare', $exportResultsAsString);
        $this->assertStringContainsString('Salaj', $exportResultsAsString);
        $this->assertStringContainsString('Sibiu', $exportResultsAsString);
        $this->assertStringContainsString('Suceava', $exportResultsAsString);
        $this->assertStringContainsString('Teleorman', $exportResultsAsString);
        $this->assertStringContainsString('Timis', $exportResultsAsString);
        $this->assertStringContainsString('Tulcea', $exportResultsAsString);
        $this->assertStringContainsString('Vaslui', $exportResultsAsString);
        $this->assertStringContainsString('Valcea', $exportResultsAsString);
        $this->assertStringContainsString('Vrancea', $exportResultsAsString);
        $this->assertStringContainsString('Bucuresti', $exportResultsAsString);
        $this->assertStringContainsString('Calarasi', $exportResultsAsString);
        $this->assertStringContainsString('Giurgiu', $exportResultsAsString);

    }

    public function testCityFailedExtraParams()
    {
        $params = $this->params;
        $params['Extra'] = 'ciocolata';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches('/^These keys are not allowed:/');

        $this->client->city($params);
    }
}
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
use Infifni\FanCourierApiClient\Request\DownloadAwb;
use Infifni\FanCourierApiClient\Request\GenerateAwb;
use Infifni\FanCourierApiClient\Request\GetAwb;
use Infifni\FanCourierApiClient\Request\TrackAwb;

class ClientAwbOperationsTest extends BaseTestCase
{
    /**
     * @var string[]
     */
    private $params = [
        'AWB' => 'testawb'
    ];

    private $generateAwbParams = [
        GenerateAwb::SERVICE_ALLOWED_VALUES['standard'] => [
            'tip_serviciu' => GenerateAwb::SERVICE_ALLOWED_VALUES['standard'],
            'banca' => '',
            'iban' =>  '',
            'nr_plicuri' => 1,
            'nr_colete' => 0,
            'greutate' => 1,
            'plata_expeditie' => 'ramburs',
            'ramburs_bani' => 100,
            'plata_ramburs_la' => 'destinatar',
            'valoare_declarata' => 400,
            'persoana_contact_expeditor' => 'Test User',
            'observatii' => 'Lorem ipsum',
            'continut' => 'Fragil',
            'nume_destinatar' => 'Test',
            'persoana_contact' => 'Test',
            'telefon' => '123456789',
            'fax' => '123456789',
            'email' => 'example@example.com',
            'judet' => 'Galati',
            'localitate' => 'Tecuci',
            'strada' => 'Lorem',
            'nr' => '2',
            'cod_postal' => '123456',
            'bl' => '',
            'scara' => '',
            'etaj'  => '',
            'apartament' => '',
            'inaltime_pachet' => '',
            'lungime_pachet' => '',
            'restituire' => '',
            'centru_cost' => '',
            'optiuni' => '',
            'packing' => '',
            'date_personale' => ''
        ],
        GenerateAwb::SERVICE_ALLOWED_VALUES['collector_account'] => []
    ];

    private $exportAwbErrorsResponseRoFields = [
        'nume',
        'judet',
        'localitate',
        'telefon',
        'plicuri',
        'colete',
        'greutate',
        'descriere'
    ];

    protected function setUp(): void
    {
        $params = $this->generateAwbParams[GenerateAwb::SERVICE_ALLOWED_VALUES['standard']];
        $params['banca'] = 'Test';
        $params['iban'] = 'XXXXXXX';
        $params['observatii'] = '';
        $params['continut'] = '';
        $params['fax'] = '';
        $this->generateAwbParams[GenerateAwb::SERVICE_ALLOWED_VALUES['collector_account']] = $params;

        parent::setUp();
    }

    /**
     * @throws Exception
     */
    public function testDeleteAwbFailedNoAwbOnApiSide()
    {
        $params = $this->params;
        $result = $this->client->deleteAwb($params);

        $this->assertStringContainsString('Error awb number.', $result);
    }

    public function testGenerateAwbSuccessWithOneStandardServiceRecord(): array
    {
        $results = $this->client->generateAwb([
            'fisier' => [
                $this->generateAwbParams[GenerateAwb::SERVICE_ALLOWED_VALUES['standard']]
            ]
        ]);

        $generatedAwbs = [];
        foreach ($results as $result) {
            $this->assertArrayHasKey('line', $result);
            $this->assertArrayHasKey('awb', $result);
            $this->assertArrayHasKey('cost', $result);
            $this->assertArrayHasKey('sent_params', $result);
            $this->assertArrayHasKey('error_message', $result);
            $this->assertSame('', $result['error_message'], 'you cannot have an error message');
            $this->assertIsNumeric($result['awb'], 'awb returned must be a number');

            $generatedAwbs[] = $result['awb'];
        }

        return $generatedAwbs;
    }

    public function testGenerateAwbSuccessWithOneAccountCollectorServiceRecord()
    {
        $results = $this->client->generateAwb([
            'fisier' => [
                $this->generateAwbParams[GenerateAwb::SERVICE_ALLOWED_VALUES['collector_account']]
            ]
        ]);

        foreach ($results as $result) {
            $this->assertArrayHasKey('line', $result);
            $this->assertArrayHasKey('awb', $result);
            $this->assertArrayHasKey('cost', $result);
            $this->assertArrayHasKey('sent_params', $result);
            $this->assertArrayHasKey('error_message', $result);
            $this->assertSame('', $result['error_message'], 'you cannot have an error message');
            $this->assertIsNumeric($result['awb'], 'awb returned must be a number');
        }
    }

    public function testGenerateAwbSuccessfulWithTwoDifferentRecords()
    {
        $results = $this->client->generateAwb([
            'fisier' => [
                $this->generateAwbParams[GenerateAwb::SERVICE_ALLOWED_VALUES['standard']],
                $this->generateAwbParams[GenerateAwb::SERVICE_ALLOWED_VALUES['collector_account']]
            ]
        ]);

        foreach ($results as $result) {
            $this->assertArrayHasKey('line', $result);
            $this->assertArrayHasKey('awb', $result);
            $this->assertArrayHasKey('cost', $result);
            $this->assertArrayHasKey('sent_params', $result);
            $this->assertArrayHasKey('error_message', $result);
            $this->assertSame('', $result['error_message'], 'you cannot have an error message');
            $this->assertIsNumeric($result['awb'], 'awb returned must be a number');
        }
    }

    public function testGenerateAwbFailedMissingRequiredParams()
    {
        $params = $this->generateAwbParams[GenerateAwb::SERVICE_ALLOWED_VALUES['standard']];
        unset($params['strada']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'strada' is required");

        $this->client->generateAwb([
            'fisier' => [
                $params
            ]
        ]);
    }

    public function testGenerateAwbFailedExtraParams()
    {
        $params = $this->generateAwbParams[GenerateAwb::SERVICE_ALLOWED_VALUES['collector_account']];
        $params['Extra'] = 'guns ... lots of guns';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches('/^These keys are not allowed: /');

        $this->client->generateAwb([
            'fisier' => [
                $params
            ]
        ]);
    }

    /**
     * @param array $generatedAwbs
     * @depends testGenerateAwbSuccessWithOneStandardServiceRecord
     */
    public function testDeleteAwbSuccessful(array $generatedAwbs)
    {
        $awbToDelete = array_shift($generatedAwbs);
        $result = $this->client->deleteAwb([
            'AWB' => $awbToDelete
        ]);

        $this->assertEquals($awbToDelete, $result, 'the AWB was not deleted successfully');
    }

    public function testDeleteAwbFailedMissingRequiredParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'AWB' is required");

        $this->client->deleteAwb([]);
    }

    public function testDeleteAwbFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^These keys are not allowed:/");

        $this->client->deleteAwb([
            'AWB' => 'testawb',
            'Extra' => 'palooza'
        ]);
    }

    /**
     * @param array $generatedAwbs
     * @depends testGenerateAwbSuccessWithOneStandardServiceRecord
     */
    public function testGetAwbSuccessful(array $generatedAwbs)
    {
        $awbToGet = array_shift($generatedAwbs);
        $result = $this->client->getAwb([
            'nr' => $awbToGet
        ]);

        $this->assertStringContainsString($awbToGet, $result, 'AWB had not appear in the print page');
        $this->assertStringContainsString(
            'Expeditorul a luat la cunoștință și a acceptat Termenii și Condițiile Generale FAN Courier',
            $result,
            'AWB had not appear in the print page'
        );
    }

    /**
     * @param array $generatedAwbs
     * @depends testGenerateAwbSuccessWithOneStandardServiceRecord
     */
    public function testGetAwbSuccessfulAllParams(array $generatedAwbs)
    {
        $awbToGet = array_shift($generatedAwbs);
        $result = $this->client->getAwb([
            'nr' => $awbToGet,
            'page' => GetAwb::PAGE_A4_ALLOWED_VALUE,
            'ln' => GetAwb::LANGUAGE_EN_ALLOWED_VALUE // this option does not change anything in english
        ]);

        $this->assertStringContainsString($awbToGet, $result, 'AWB had not appear in the print page');
        $this->assertStringContainsString(
            'Expeditorul a luat la cunoștință și a acceptat Termenii și Condițiile Generale FAN Courier',
            $result,
            'AWB had not appear in the print page'
        );
    }

    public function testGetAwbFailedMissingParams()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'nr' is required");

        $this->client->getAwb([]);
    }

    public function testGetAwbFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^These keys are not allowed: /");

        $this->client->getAwb([
            'nr' => 'testawb',
            'Extra' => 'you'
        ]);
    }

    /**
     * @param array $generatedAwbs
     * @depends testGenerateAwbSuccessWithOneStandardServiceRecord
     */
    public function testTrackAwbSuccessfulAllParams(array $generatedAwbs)
    {
        $awbToTrack = array_shift($generatedAwbs);
        $result = $this->client->trackAwb([
            'AWB' => $awbToTrack,
            'display_mode' => TrackAwb::DISPLAY_MODE_LAST_STATUS_ALLOWED_VALUE,
            'language' => TrackAwb::LANGUAGE_EN_ALLOWED_VALUE // this option does not change anything in english
        ]);

        $this->assertStringContainsString('0', $result);
        $this->assertStringContainsString('The AWB number was generated by the sender customer', $result);
    }

    /**
     * @param array $generatedAwbs
     * @depends testGenerateAwbSuccessWithOneStandardServiceRecord
     */
    public function testTrackAwbSuccessfulRequiredParams(array $generatedAwbs)
    {
        $awbToTrack = array_shift($generatedAwbs);
        $result = $this->client->trackAwb([
            'AWB' => $awbToTrack,
            'display_mode' => TrackAwb::DISPLAY_MODE_LAST_STATUS_ALLOWED_VALUE
        ]);

        $this->assertStringContainsString('0', $result);
        $this->assertStringContainsString('AWB-ul a fost inregistrat de catre clientul expeditor.', $result);
    }

    public function testTrackAwbFailedMissingParams()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'AWB' is required");

        $this->client->trackAwb([]);
    }

    public function testTrackAwbFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^These keys are not allowed: /");

        $this->client->trackAwb([
            'AWB' => 'testawb',
            'display_mode' => TrackAwb::DISPLAY_MODE_ALL_ALLOWED_VALUE,
            'Extra' => 'you'
        ]);
    }

    /**
     * @param array $generatedAwbs
     * @depends testGenerateAwbSuccessWithOneStandardServiceRecord
     */
    public function testDownloadAwbSuccessfulAllParams(array $generatedAwbs)
    {
        $awbToDownload = array_shift($generatedAwbs);
        $result = $this->client->downloadAwb([
            'AWB' => $awbToDownload,
            'language' => DownloadAwb::LANGUAGE_EN_ALLOWED_VALUE // this option does not change anything in english
        ]);

        $this->assertStringContainsString('Image not available', $result);
    }

    /**
     * @param array $generatedAwbs
     * @depends testGenerateAwbSuccessWithOneStandardServiceRecord
     */
    public function testDownloadAwbSuccessfulRequiredParams(array $generatedAwbs)
    {
        $awbToDownload = array_shift($generatedAwbs);
        $result = $this->client->downloadAwb([
            'AWB' => $awbToDownload
        ]);

        $this->assertStringContainsString('Imaginea nu este disponibila', $result);
    }

    public function testDownloadAwbFailedMissingParams()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'AWB' is required");

        $this->client->downloadAwb([]);
    }

    public function testDownloadAwbFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^These keys are not allowed: /");

        $this->client->downloadAwb([
            'AWB' => 'testawb',
            'Extra' => 'what'
        ]);
    }

    public function testExportAwbErrorsSuccessful()
    {
        $results = $this->client->exportAwbErrors();

        $this->assertIsArray($results);
        foreach ($results as $result) {
            foreach ($this->exportAwbErrorsResponseRoFields as $field) {
                $this->assertArrayHasKey($field, $result);
            }
        }
    }

    public function testExportAwbErrorsFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("No fields required");

        $this->client->exportAwbErrors([
            'Extra' => 'what'
        ]);
    }
}
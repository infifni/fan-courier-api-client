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
use Infifni\FanCourierApiClient\Request\ExportBordereau;

class ClientBordereauOperationsTest extends BaseTestCase
{
    /**
     * @var string[]
     */
    private $exportBordereauRoResponseValidFields = [
        'nr._crt.',
        'awb',
        'client_dest',
        'telefon_dest',
        'stradadestinatar',
        'nrdestinatar',
        'blocdestinatar',
        'scaradestinatar',
        'etajdestinatar',
        'apdestinatar',
        'oras_dest',
        'orasel',
        'plic',
        'colet',
        'kg',
        'continut',
        'plata_la',
        'val._decl.',
        'obs.',
        'persexpeditor',
        'persdest',
        'depnr',
        'kmextdest',
        'data_awb',
        'ora_awb',
        'ridicat',
        'centru_cost',
        'status',
        'data_confirmarii',
        'ora_confirmarii',
        'nume_confirmare',
        'client_exp',
        'restituire',
        'tip_serviciu',
        'banca',
        'iban',
        'awb_retur'
    ];

    private $exportBordereauEnResponseValidFields = [
        'crt._no.',
        'awb',
        'recipient',
        'recipient_phone',
        'recipient_street',
        'recipient_street_number',
        'recipient_block',
        'recipient_entrance',
        'recipient_floor',
        'recipient_flat',
        'recipient_town',
        'envelopes',
        'parcels',
        'weight(kg)',
        'shipment_contents',
        'payment_of_shipment_at',
        'declared_value',
        'reimbursement',
        'observations',
        'sender_contact_person',
        'recipient_contact_person',
        'recipient_fan_agency_(dep._no.)_',
        'recipient_external_km',
        'awb_date',
        'awb_hour',
        'raised',
        'cost_center',
        'status',
        'confirmation_date',
        'confirmation_hour',
        'confirmation_name',
        'sender_name',
        'refund',
        'service_type',
        'bank',
        'iban',
        'return_awb'
    ];

    /**
     * @Depends clientAwbOperationsTest::testGenerateAwbSuccessfulWithTwoDifferentRecords
     */
    public function testEndBordereauSuccessful()
    {
        $result = $this->client->endBordereau();

        $this->assertStringContainsString('Total plata expeditor (Fara TVA)', $result);
        $this->assertStringContainsString('Total plata expeditor (cu TVA)', $result);
        $this->assertStringContainsString('Total expeditii', $result);
        $this->assertStringContainsString('Greutate totala', $result);
        $this->assertStringContainsString('Am predat', $result);
        $this->assertStringContainsString('Am primit', $result);
    }

    public function testEndBordereauFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage('No fields required');

        $this->client->endBordereau(['Extra' => 'extra']);
    }

    /**
     * @Depends clientAwbOperationsTest::testGenerateAwbSuccessfulWithTwoDifferentRecords
     * @throws Exception
     */
    public function testExportBordereauSuccessfulRequiredParam()
    {
        $results = $this->client->exportBordereau([
            'data' => date('d.m.Y')
        ]);

        $nrResults = count($results);
        $step = $nrResults / 10;
        for ($index = 0; $index < $nrResults;) {
            foreach ($this->exportBordereauRoResponseValidFields as $validField) {
                $this->assertArrayHasKey($validField, $results[$index]);
            }

            $index += random_int(1, $step > 1 ? $step : 2);
        }
    }

    /**
     * @Depends clientAwbOperationsTest::testGenerateAwbSuccessfulWithTwoDifferentRecords
     * @throws Exception
     */
    public function testExportBordereauSuccessfulAllParams()
    {
        $results = $this->client->exportBordereau([
            'data' => date('d.m.Y'),
            'language' => ExportBordereau::LANGUAGE_EN_ALLOWED_VALUE,
            'mode' => ExportBordereau::MODE_ONLY_SELFAWB_ALLOWED_VALUE
        ]);

        $nrResults = count($results);
        $step = $nrResults / 10;
        for ($index = 0; $index < $nrResults;) {
            foreach ($this->exportBordereauEnResponseValidFields as $validField) {
                $this->assertArrayHasKey($validField, $results[$index]);
            }

            $index += random_int(1, $step > 1 ? $step : 2);
        }
    }

    public function testExportBordereauFailedMissingRequiredParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'data' is required");

        $this->client->exportBordereau([]);
    }

    public function testExportBordereauFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches('/^These keys are not allowed:/');

        $this->client->exportBordereau([
            'data' => date('d.m.Y'),
            'Extra' => 'extra'
        ]);
    }

    public function testExportBordereauFailedNotAllowedValue()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^Field 'mode' can have one of the following values: /");

        $this->client->exportBordereau([
            'data' => date('d.m.Y'),
            'mode' => 'extra'
        ]);
    }
}
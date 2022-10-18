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
use Infifni\FanCourierApiClient\Request\ExportReports;

class ClientExportReportsTest extends BaseTestCase
{
    /**
     * @var string[]
     */
    private $roResponseValidFields = [
        'oras_destinatar',
        'data_awb',
        'suma_incasata',
        'numar_awb',
        'expeditor',
        'destinatar',
        'continut',
        'persoanad',
        'data_virament',
        'persoanae',
        'ramburs_la_awb',
        'awb_retur',
        'incasare_card'
    ];

    private $enResponseValidFields = [
        'recipient_city',
        'awb_date',
        'amount_received',
        'awb_number',
        'sender',
        'recipient',
        'content',
        'recipient_contact_person',
        'bank_transfer_date',
        'sender_contact_person',
        'awb_reimbursement',
        'return_awb',
        'card_payment'
    ];

    /**
     * @throws Exception
     */
    public function testExportReportsSuccessfulRequiredParam()
    {
        $results = $this->client->exportReports([
            'data' => date('d.m.Y')
        ]);

        $nrResults = count($results);
        $step = $nrResults / 10;
        $this->assertIsArray($results);
        for ($index = 0; $index < $nrResults;) {
            foreach ($this->roResponseValidFields as $validField) {
                $this->assertArrayHasKey($validField, $results[$index]);
            }

            $index += random_int(1, $step);
        }
    }

    /**
     * @throws Exception
     */
    public function testExportReportsSuccessfulAllParams()
    {
        $results = $this->client->exportReports([
            'data' => date('d.m.Y'),
            'language' => ExportReports::LANGUAGE_EN_ALLOWED_VALUE
        ]);

        $nrResults = count($results);
        $step = $nrResults / 10;
        $this->assertIsArray($results);
        for ($index = 0; $index < $nrResults;) {
            foreach ($this->enResponseValidFields as $validField) {
                $this->assertArrayHasKey($validField, $results[$index]);
            }

            $index += random_int(1, $step);
        }
    }

    public function testExportReportsFailedMissingRequiredParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'data' is required");

        $this->client->exportReports([]);
    }

    public function testExportReportsFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches('/^These keys are not allowed:/');

        $this->client->exportReports([
            'data' => date('d.m.Y'),
            'Extra' => 'extra'
        ]);
    }

    public function testExportReportsFailedNotAllowedValue()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^Field 'language' can have one of the following values: /");

        $this->client->exportReports([
            'data' => date('d.m.Y'),
            'language' => 'extra'
        ]);
    }
}
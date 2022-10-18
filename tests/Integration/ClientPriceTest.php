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
use Infifni\FanCourierApiClient\Request\Price;

class ClientPriceTest extends BaseTestCase
{
    private $standardServiceParams = [
        'serviciu' => Price::SERVICE_ALLOWED_VALUES['standard'],
        'localitate_dest' => 'Ghighiseni',
        'judet_dest' => 'Bihor',
        'plicuri' => '0',
        'colete' => '1',
        'greutate' => '2',
        'lungime' => '40',
        'latime' => '40',
        'inaltime' => '40',
        'val_decl' => '',
        'plata_ramburs' => Price::RECIPIENT_ALLOWED_VALUE,
    ];

    private $exportServiceParams = [
        'serviciu' => Price::SERVICE_ALLOWED_VALUES['export'],
        'modtrim' => Price::SEND_MODE_BY_CAR_ALLOWED_VALUE,
        'greutate' => '2',
        'pliccolet' => '1',
        's_inaltime' => '20',
        's_latime' => '60',
        's_lungime' => '60',
        'dest_tara' => 'Germania',
        'tipcontinut' => Price::CONTENT_TYPE_NON_DOCUMENT_ALLOWED_VALUE,
        'km_ext' => '600',
    ];

    public function testValidateSuccessfulStandardService()
    {
        $result = $this->client->price($this->standardServiceParams);

        $this->assertIsNumeric($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testValidateSuccessfulStandardServiceWithEnvelopes()
    {
        $params = $this->standardServiceParams;
        $params['greutate'] = '0.5';
        $params['colete'] = '0';
        $params['plicuri'] = '30';
        unset($params['inaltime'], $params['latime'], $params['lungime']);
        $result = $this->client->price($params);

        $this->assertIsNumeric($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testValidateSuccessfulStandardServiceWithOptionalParam()
    {
        $params = $this->standardServiceParams;
        $params['plata_la'] = Price::SENDER_ALLOWED_VALUE;
        $result = $this->client->price($params);

        $this->assertIsNumeric($result);
    }

    public function testValidateFailedMissingServiceParam()
    {
        $params = $this->standardServiceParams;
        unset($params['serviciu']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^Must set a field 'serviciu' with one of these values/");

        $this->client->price($params);
    }

    public function testValidateFailedStandardServiceMissingRequiredParams()
    {
        $params = $this->standardServiceParams;
        unset($params['localitate_dest']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'localitate_dest' is required");

        $this->client->price($params);
    }

    public function testValidateFailedStandardServiceValueNotAllowed()
    {
        $params = $this->standardServiceParams;
        $params['plata_ramburs'] = 'bunny';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches(
            "/^Field 'plata_ramburs' can have one of the following values\:.*$/"
        );

        $this->client->price($params);
    }

    public function testValidateFailedStandardServiceExtraFields()
    {
        $params = $this->standardServiceParams;
        $params['it_is_a_bomb'] = 'yes';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches(
            "/^These keys are not allowed: /"
        );

        $this->client->price($params);
    }

    public function testValidateSuccessfulExportService()
    {
        $result = $this->client->price($this->exportServiceParams);

        $this->assertIsString($result);
        $this->assertStringContainsString('tarif', $result);
        $this->assertStringContainsString('gvol', $result);
    }

    public function testValidateFailedExportServiceMissingParams()
    {
        $params = $this->exportServiceParams;
        unset($params['modtrim']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'modtrim' is required");

        $this->client->price($params);
    }

    public function testValidateFailedExportServiceValueNotAllowed()
    {
        $params = $this->exportServiceParams;
        $params['tipcontinut'] = 'unicorn';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^Field 'tipcontinut' can have one of the following values\:.*$/");

        $this->client->price($params);
    }
}
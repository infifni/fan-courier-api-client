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
use Infifni\FanCourierApiClient\Request\Price;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
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
        $price = new Price();

        $this->assertTrue($price->validate($this->standardServiceParams), 'expected successful validation');
    }

    public function testValidateFailedMissingServiceParam()
    {
        $price = new Price();
        $params = $this->standardServiceParams;
        unset($params['serviciu']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^Must set a field 'serviciu' with one of these values/");

        $price->validate($params);
    }

    public function testValidateSuccessfulStandardServiceWithOptionalParam()
    {
        $price = new Price();
        $params = $this->standardServiceParams;
        $params['plata_la'] = Price::SENDER_ALLOWED_VALUE;

        $this->assertTrue($price->validate($params), 'expected successful validation');
    }

    public function testValidateFailedStandardServiceMissingRequiredParams()
    {
        $price = new Price();
        $params = $this->standardServiceParams;
        unset($params['inaltime']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'inaltime' is required");

        $price->validate($params);
    }

    public function testValidateFailedStandardServiceValueNotAllowed()
    {
        $price = new Price();
        $params = $this->standardServiceParams;
        $params['plata_ramburs'] = 'bunny';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches(
            "/^Field 'plata_ramburs' can have one of the following values\:.*$/"
        );

        $price->validate($params);
    }

    public function testValidateFailedStandardServiceExtraFields()
    {
        $price = new Price();
        $params = $this->standardServiceParams;
        $params['it_is_a_bomb'] = 'yes';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches(
            "/^The only keys accepted are: /"
        );

        $price->validate($params);
    }

    public function testValidateSuccessfulExportService()
    {
        $price = new Price();

        $this->assertTrue($price->validate($this->exportServiceParams), 'expected successful validation');
    }

    public function testValidateFailedExportServiceMissingParams()
    {
        $price = new Price();
        $params = $this->exportServiceParams;
        unset($params['modtrim']);

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'modtrim' is required");

        $price->validate($params);
    }

    public function testValidateFailedExportServiceValueNotAllowed()
    {
        $price = new Price();
        $params = $this->exportServiceParams;
        $params['tipcontinut'] = 'unicorn';

        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches("/^Field 'tipcontinut' can have one of the following values\:.*$/");

        $price->validate($params);
    }
}
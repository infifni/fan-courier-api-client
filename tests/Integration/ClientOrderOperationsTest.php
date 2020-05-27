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
use Infifni\FanCourierApiClient\Request\ExportOrders;

class ClientOrderOperationsTest extends BaseTestCase
{
    /**
     * @var array
     */
    private $exportOrdersResponseRoFields = [
        'nr._crt.',
        'data_ridicare_comanda',
        'ora_de_la',
        'ora_pana_la',
        'persoana_contact',
        'telefon',
        'email',
        'colete',
        'plicuri',
        'greutate',
        'inaltime',
        'latime',
        'lungime',
        'observatii',
        'strada',
        'nr.',
        'bloc',
        'scara',
        'etaj',
        'ap.',
        'localitate',
        'judet',
        'numar_comanda',
        'status'
    ];

    /**
     * @var array
     */
    private $exportOrdersResponseEnFields = [
        'crt._no.',
        'date_picked_order',
        'hour_from',
        'hour_to',
        'contact_person',
        'telephone',
        'email',
        'parcels',
        'envelopes',
        'weight',
        'height',
        'width',
        'length',
        'observations',
        'street',
        'street_number',
        'block',
        'entrance',
        'floor',
        'flat',
        'city',
        'county',
        'order_number',
        'status'
    ];

    private $params = [
        'pers_contact' => 'Cocos Laurentiu',
        'tel' => '0774230173',
        'email' => 'lco@infifnisoftware.ro',
        'greutate' => '1',
        'inaltime' => '15',
        'lungime' => '30',
        'latime' => '20',
        'ora_ridicare' => '13:00',
        'nr_plicuri' => '',
        'nr_colete' => '1',
        'observatii' => 'fragil',
        'client_exp' => 'Cocos Laurentiu',
        'strada' => '',
        'nr' => '',
        'bloc' => '',
        'scara' => '',
        'etaj' => '',
        'ap' => '',
        'localitate' => '',
        'judet' => '',
    ];

    public function setUp(): void
    {
        $this->params['ora_ridicare'] = date('H:i', time() + 3600);

        parent::setUp();
    }

    public function testOrderSuccessfulMostCommonParams()
    {
        $result = $this->client->order($this->params);

        $this->assertIsString($result);
        $hour = date('H') + 1;
        if (9 <= $hour && $hour <= 17) {
            $this->assertStringContainsString('Inregistrarea comenzii este finalizata cu SUCCES.', $result);
        } else {
            $this->assertStringContainsString(
                'Ora de ridicare incorecta. Intervalul de ridicare comenzi este de luni pana vineri intre orele'.
                ' 09:00 - 19:00 si sambata intre orele 09:00 - 14:00. Comanda trebuie adaugata inainte de ora indicata.',
                $result
            );
        }
    }

    public function testOrderFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches('/^The only keys accepted are: /');

        $params = $this->params;
        $params['extra'] = 'new';
        $this->client->order($params);
    }

    public function testOrderFailedRequiredHeightIfEnoughWeight()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'inaltime' is required");

        $params = $this->params;
        $params['greutate'] = '3';
        $params['inaltime'] = '';
        $this->client->order($params);
    }

    public function testOrderFailedRequiredStreetNumberIfStreetSpecified()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'nr' is required");

        $params = $this->params;
        $params['strada'] = 'Decebal';
        $this->client->order($params);
    }

    public function testOrderFailedNumberOfParcelsGreaterOrEqualToOneIfSpecified()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessage("Field 'nr_colete' must be at least 1");

        $params = $this->params;
        $params['nr_colete'] = '0';
        $this->client->order($params);
    }

    /**
     * @depends testOrderSuccessfulMostCommonParams
     */
    public function testExportOrdersSuccessfulOnlyRequiredParam()
    {
        $results = $this->client->exportOrders([
            'data' => date('d.m.Y')
        ]);

        $this->assertIsArray($results);
        foreach ($results as $result) {
            foreach ($this->exportOrdersResponseRoFields as $field) {
                $this->assertArrayHasKey($field, $result);
            }
        }
    }

    /**
     * @depends testOrderSuccessfulMostCommonParams
     */
    public function testExportOrdersSuccessfulOnlyAllParams()
    {
        $results = $this->client->exportOrders([
            'data' => date('d.m.Y'),
            'language' => ExportOrders::LANGUAGE_EN_ALLOWED_VALUE
        ]);

        $this->assertIsArray($results);
        foreach ($results as $result) {
            foreach ($this->exportOrdersResponseEnFields as $field) {
                $this->assertArrayHasKey($field, $result);
            }
        }
    }

    public function testExportOrdersFailedExtraParam()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches('/^The only keys accepted are: /');

        $this->client->exportOrders([
            'data' => date('d.m.Y'),
            'Extra' => 'cheese'
        ]);
    }

    public function testExportOrdersFailedNotAllowedValue()
    {
        $this->expectException(FanCourierInvalidParamException::class);
        $this->expectExceptionMessageMatches(
            "/^Field 'language' can have one of the following values: /"
        );

        $this->client->exportOrders([
            'data' => date('d.m.Y'),
            'language' => 'it'
        ]);
    }
}
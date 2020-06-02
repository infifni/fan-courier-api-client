<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Request;

use Exception;
use Infifni\FanCourierApiClient\Exception\FanCourierInvalidParamException;

class GenerateAwb extends Endpoint implements CsvFileRequestInterface
{
    const API_SUBMIT_ROW_SUCCESSFUL = '1';
    const RECIPIENT_ALLOWED_VALUE = 'destinatar';
    const SENDER_ALLOWED_VALUE = 'expeditor';

    protected $keys;

    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'import_awb_integrat.php';
    }

    /**
     * @return string
     */
    public function getApiResultType(): string
    {
        return EndpointInterface::API_RESULT_TYPE_PARSE;
    }

    /**
     *
     * @param string $result
     * @return array|string
     */
    public function parseResult(string $result): array
    {
        $parse = str_getcsv($result, "\n");
        if (empty($parse)) {
            return $result;
        }

        $returnResult = [];
        try {
            $requestParams = $this->getRequestParams();
            foreach ($parse as $value) {
                $resultPerRow = explode(',', $value);
                if (empty($resultPerRow[0]) && empty($resultPerRow[1]) && empty($resultPerRow[2])) {
                    continue;
                }
                $returnResult[] = [
                    'line' => (int) $resultPerRow[0],
                    'awb' => self::API_SUBMIT_ROW_SUCCESSFUL === $resultPerRow[1] ? $resultPerRow[2] : false,
                    'cost' => self::API_SUBMIT_ROW_SUCCESSFUL === $resultPerRow[1] ? $resultPerRow[3] : false,
                    'sent_params' => current(array_values($requestParams))[(int) $resultPerRow[0] - 1],
                    'error_message' => self::API_SUBMIT_ROW_SUCCESSFUL === $resultPerRow[1] ? '' : $resultPerRow[2]];
            }
        } catch (Exception $ex) {
            $returnResult[] = [
                'line' => 1,
                'awb' => false,
                'cost' => false,
                'sent_params' => false,
                'error_message' => $ex->getMessage()
            ];
        }

        return $returnResult;
    }

    /**
     *
     * @param array $params
     * @return boolean
     * @throws FanCourierInvalidParamException
     */
    public function validate(array $params): bool
    {
        // this is the array data that contains the details about what dispatches need to be imported
        // this will be converted into a CSV file
        // the data corresponds to the import AWBs model from FAN application, it can contain one or more dispatches
        if (empty($params['fisier']) || ! is_array($params['fisier'])) {
            throw new FanCourierInvalidParamException(
                "Must set a field 'fisier' containing multiple arrays."
            );
        }

        $serviceAllowedValues = self::SERVICE_ALLOWED_VALUES;
        unset($serviceAllowedValues['export']);
        foreach ($params['fisier'] as $serviceParams) {
            $serviceType = $serviceParams['tip_serviciu'] ?? null;
            if (
                empty($serviceType)
                ||
                ! in_array($serviceType, $serviceAllowedValues, true)
            ) {
                throw new FanCourierInvalidParamException(
                    "Must set a field 'tip_serviciu' with one of these values: " . implode(', ', $serviceAllowedValues)
                );
            }

            $this->validateAgainst($serviceParams, $this->getFieldRules());
        }

        return true;
    }

    /**
     *
     * @return array
     */
    private function getFieldRules(): array
    {
        $serviceAllowedValues = self::SERVICE_ALLOWED_VALUES;
        unset($serviceAllowedValues['export']);
        return [
            'tip_serviciu' => [
                'required' => true,
                'allowed_values' => $serviceAllowedValues
            ],
            'banca' => [
                'required' => false
            ],
            'iban' => [
                'required' => false
            ],
            'nr_plicuri' => [
                'required' => true
            ],
            'nr_colete' => [
                'required' => true
            ],
            'greutate' => [
                'required' => true
            ],
            'plata_expeditie' => [
                'required' => true
            ],
            'ramburs_bani' => [
                'required' => true
            ],
            'plata_ramburs_la' => [
                'required' => true,
                'allowed_values' => [
                    self::RECIPIENT_ALLOWED_VALUE,
                    self::SENDER_ALLOWED_VALUE
                ]
            ],
            'valoare_declarata' => [
                'required' => false
            ],
            'persoana_contact_expeditor' => [
                'required' => true
            ],
            'observatii' => [
                'required' => false
            ],
            'continut' => [
                'required' => false
            ],
            'nume_destinatar' => [
                'required' => true
            ],
            'persoana_contact' => [
                'required' => true
            ],
            'telefon' => [
                'required' => true
            ],
            'fax' => [
                'required' => false
            ],
            'email' => [
                'required' => true
            ],
            'judet' => [
                'required' => true
            ],
            'localitate' => [
                'required' => true
            ],
            'strada' => [
                'required' => true
            ],
            'nr' => [
                'required' => true
            ],
            'cod_postal' => [
                'required' => true
            ],
            'bl' => [
                'required' => false
            ],
            'scara' => [
                'required' => false
            ],
            'etaj' => [
                'required' => false
            ],
            'apartament' => [
                'required' => false
            ],
            'inaltime_pachet' => [
                'required' => false
            ],
            'latime_pachet' => [
                'required' => false
            ],
            'lungime_pachet' => [
                'required' => false
            ],
            'restituire' => [
                'required' => false
            ],
            'centru_cost' => [
                'required' => false
            ],
            'optiuni' => [
                'required' => false
            ],
            'packing' => [
                'required' => false
            ],
            'date_personale' => [
                'required' => false
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCsvHeaders(): array
    {
        return [
            'tip_serviciu' => 'Tip serviciu',
            'banca' => 'Banca',
            'iban' => 'IBAN',
            'nr_plicuri' => 'Nr. Plicuri',
            'nr_colete' => 'Nr. Colete',
            'greutate' => 'Greutate',
            'plata_expeditie' => 'Plata expeditie',
            'ramburs_bani' => 'Ramburs(bani)',
            'plata_ramburs_la' => 'Plata ramburs la',
            'valoare_declarata' => 'Valoare declarata',
            'persoana_contact_expeditor' => 'Persoana contact expeditor',
            'observatii' => 'Observatii',
            'continut' => 'Continut',
            'nume_destinatar' => 'Nume destinatar',
            'persoana_contact' => 'Persoana contact',
            'telefon' => 'Telefon',
            'fax' => 'Fax',
            'email' => 'Email',
            'judet' => 'Judet',
            'localitate' => 'Localitatea',
            'strada' => 'Strada',
            'nr' => 'Nr',
            'cod_postal' => 'Cod postal',
            'bl' => 'Bloc',
            'scara' => 'Scara',
            'etaj' => 'Etaj',
            'apartament' => 'Apartament',
            'inaltime_pachet' => 'Inaltime pachet',
            'latime_pachet' => 'Latime pachet',
            'lungime_pachet' => 'Latime pachet',
            'restituire' => 'Restituire',
            'centru_cost' => 'Centru Cost',
            'optiuni' => 'Optiuni',
            'packing' => 'Packing',
            'date_personale' => 'Date personale',
        ];
    }
}


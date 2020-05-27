<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Request;

use Infifni\FanCourierApiClient\Exception\FanCourierInvalidParamException;
use Infifni\FanCourierApiClient\Exception\FanCourierUnknownRequestException;

class Price extends Endpoint
{
    const RECIPIENT_ALLOWED_VALUE = 'destinatar';
    const SENDER_ALLOWED_VALUE = 'expeditor';
    const CONTENT_TYPE_DOCUMENT_ALLOWED_VALUE = 1;
    const CONTENT_TYPE_NON_DOCUMENT_ALLOWED_VALUE = 2;
    const SEND_MODE_BY_AIR_ALLOWED_VALUE = 'aerian';
    const SEND_MODE_BY_CAR_ALLOWED_VALUE = 'rutier';

    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'tarif.php';
    }

    /**
     * @return string
     */
    public function getApiResultType(): string
    {
        return EndpointInterface::API_RESULT_TYPE_PLAIN;
    }

    /**
     * @param array $params
     * @return boolean
     * @throws FanCourierInvalidParamException
     * @throws FanCourierUnknownRequestException
     */
    public function validate(array $params): bool
    {
        if (
            empty($params['serviciu'])
            ||
            ! in_array($params['serviciu'], self::SERVICE_ALLOWED_VALUES, true)
        ) {
            throw new FanCourierInvalidParamException(
                "Must set a field 'serviciu' with one of these values: ".implode(', ', self::SERVICE_ALLOWED_VALUES)
            );
        }

        $service = $params['serviciu'];
        unset($params['serviciu']);
        if (self::SERVICE_ALLOWED_VALUES['export'] !== $service) {
            $this->validateAgainst($params, $this->getInternalServiceFieldRules());

            $additionalRules = [];
            if ($params['colete'] > 0 || $params['greutate'] > 1) {
                $additionalRules['inaltime'] = $additionalRules['latime'] = $additionalRules['lungime'] = [
                    'required' => true
                ];
            }

            return $this->validateAgainst($params, $additionalRules, false);
        }

        return $this->validateAgainst($params, $this->getExportServiceFieldRules());
    }

    /**
     * @return array
     */
    private function getInternalServiceFieldRules(): array
    {
        return [
            'localitate_dest' => [ // the name of the destination locality (from FAN database)
                'required' => true
            ],
            'judet_dest' => [ // the name of the destination county (from FAN database)
                'required' => true
            ],
            'plicuri' => [ // the number of envelopes
                'required' => true
            ],
            'colete' => [ // the number of parcels
                'required' => true
            ],
            'greutate' => [ // total weight (kg)
                'required' => true
            ],
            'lungime' => [ // length (cm)
                'required' => false // required only for parcels or if weight > 1 (centimetres)
            ],
            'latime' => [ // width (cm)
                'required' => false // required only for parcels or if weight > 1 (centimetres)
            ],
            'inaltime' => [ // height (cm)
                'required' => false // required only for parcels or if weight > 1 (centimetres)
            ],
            'val_decl' => [ // declared value
                'required' => false
            ],
            'plata_ramburs' => [ // repayment at "sender" ("expeditor") or "recipient" ("destinatar")
                'required' => true,
                'allowed_values' => [
                    self::SENDER_ALLOWED_VALUE, self::RECIPIENT_ALLOWED_VALUE
                ]
            ],
            'plata_la' => [ // payment done at "sender" ("expeditor") or "recipient" ("destinatar") which is optional
                'required' => false,
                'allowed_values' => [
                    self::SENDER_ALLOWED_VALUE, self::RECIPIENT_ALLOWED_VALUE
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    private function getExportServiceFieldRules(): array
    {
        return [
            'modtrim' => [ // the way of how the package is sent
                'required' => true,
                'allowed_values' => [
                    self::SEND_MODE_BY_AIR_ALLOWED_VALUE,
                    self::SEND_MODE_BY_CAR_ALLOWED_VALUE
                ]
            ],
            'greutate' => [ // total weight (kg, 2 decimals)
                'required' => true
            ],
            'pliccolet' => [ // the number of parcels
                'required' => true
            ],
            's_inaltime' => [ // the sum of all the parcel heights
                'required' => true
            ],
            's_latime' => [ // the sum of all the parcel widths
                'required' => true
            ],
            's_lungime' => [ // the sum of all the parcel lengths
                'required' => true
            ],
            'dest_tara' => [ // name of the destination country
                'required' => true
            ],
            'tipcontinut' => [ // 1 for document and 2 for non document
                'required' => true,
                'allowed_values' => [
                    self::CONTENT_TYPE_DOCUMENT_ALLOWED_VALUE,
                    self::CONTENT_TYPE_NON_DOCUMENT_ALLOWED_VALUE
                ]
            ],
            'km_ext' => [ // external kilometres at recipient
                'required' => true
            ]
        ];
    }
}


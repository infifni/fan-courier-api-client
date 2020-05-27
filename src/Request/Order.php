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

class Order extends Endpoint
{
    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'comanda_curier_integrat.php';
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
     */
    public function validate(array $params): bool
    {
        $this->validateAgainst($params, $this->getFieldRules());

        $additionalRules = [];
        if ($params['nr_colete'] > 0 || $params['greutate'] > 1) {
            $additionalRules['inaltime'] = $additionalRules['latime'] = $additionalRules['lungime'] = [
                'required' => true
            ];
        }
        if (isset($params['strada']) && '' !== $params['strada']) {
            foreach (['nr', 'localitate', 'judet'] as $field) {
                $additionalRules[$field] = [
                    'required' => true
                ];
            }
        }

        $this->validateAgainst($params, $additionalRules, false);

        foreach (['nr_colete', 'nr_plicuri'] as $field) {
            if (isset($params[$field]) && '' !== $params[$field] && $params[$field] < 1) {
                throw new FanCourierInvalidParamException("Field '$field' must be at least 1");
            }
        }

        return true;
    }
    
    /**
     * @return array
     */
    private function getFieldRules(): array
    {
        return [
            'pers_contact' => [
                'required' => true
            ],
            'tel' => [
                'required' => true
            ],
            'email' => [
                'required' => true
            ],
            'greutate' => [ // required, for envelopes insert 1
                'required' => true
            ],
            'inaltime' => [ // required only for parcels or if weight > 1 (centimetres)
                'required' => false
            ],
            'lungime' => [ // required only for parcels or if weight > 1 (centimetres)
                'required' => false
            ],
            'latime' => [ // required only for parcels or if weight > 1 (centimetres)
                'required' => false
            ],
            'ora_ridicare' => [ // required, format: hh:mm
                'required' => true,
                'regex' => '/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'
            ],
            'nr_plicuri' => [ // optional, must be at least one envelope if specified
                'required' => false
            ],
            'nr_colete' => [ // optional, must be at least one parcel if specified
                'required' => false
            ],
            'observatii' => [
                'required' => false
            ],
            'client_exp' => [ // sender name, different from the subsidiary name
                'required' => false
            ],
            'strada' => [ // optional, only for orders with pickup address different from client address
                'required' => false
            ],
            'nr' => [ // optional, only for orders with pickup address different from client address
                'required' => false
            ],
            'bloc' => [ // optional, only for orders with pickup address different from client address
                'required' => false
            ],
            'scara' => [ // optional, only for orders with pickup address different from client address
                'required' => false
            ],
            'etaj' => [ // optional, only for orders with pickup address different from client address
                'required' => false
            ],
            'ap' => [ // optional, only for orders with pickup address different from client address
                'required' => false
            ],
            'localitate' => [ // required when field 'strada' is used
                'required' => false
            ],
            'judet' => [ // required when field 'strada' is used
                'required' => false
            ]
        ];
    }
}


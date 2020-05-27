<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Request;

class City extends Endpoint
{
    const LANGUAGE_RO_ALLOWED_VALUE = 'ro';
    const LANGUAGE_EN_ALLOWED_VALUE = 'en';

    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'export_distante_integrat.php';
    }

    /**
     * @return string
     */
    public function getApiResultType(): string
    {
        return EndpointInterface::API_RESULT_TYPE_CSV;
    }

    /**
     * @param array $params
     * @return boolean
     */
    public function validate(array $params = []): bool
    {
        return $this->validateAgainst($params, $this->getFieldRules());
    }

    /**
     * @return array
     */
    private function getFieldRules(): array
    {
        return [
            'judet' => [
                'required' => false
            ],
            'language' => [
                'required' => false,
                'allowed_values' => [
                    self::LANGUAGE_RO_ALLOWED_VALUE,
                    self::LANGUAGE_EN_ALLOWED_VALUE
                ]
            ]
        ];
    }
}



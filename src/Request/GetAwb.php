<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Request;

class GetAwb extends Endpoint
{
    const PAGE_A4_ALLOWED_VALUE = 'A4';
    const PAGE_A5_ALLOWED_VALUE = 'A5';
    const PAGE_A6_ALLOWED_VALUE = 'A6';
    const LANGUAGE_RO_ALLOWED_VALUE = 'ro';
    const LANGUAGE_EN_ALLOWED_VALUE = 'en';

    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'view_awb_integrat.php';
    }

    /**
     * @return string
     */
    public function getApiResultType(): string
    {
        return EndpointInterface::API_RESULT_TYPE_HTML;
    }

    /**
     * 
     * @param string $result
     * @return int|string
     */
    public function parseResult($result) 
    {
        return $result;
    }

    /**
     * @param array $params
     * @return boolean
     */
    public function validate(array $params): bool
    {
        $this->validateAgainst($params, $this->getFieldRules());

        return true;
    }

    /**
     * @return array
     */
    private function getFieldRules(): array
    {
        return [
            'nr' => [ // AWB
                'required' => true
            ],
            'page' => [
                'required' => false,
                'allowed_values' => [
                    self::PAGE_A4_ALLOWED_VALUE,
                    self::PAGE_A5_ALLOWED_VALUE,
                    self::PAGE_A6_ALLOWED_VALUE
                ]
            ],
            'ln' => [
                'required' => false,
                'allowed_values' => [
                    self::LANGUAGE_RO_ALLOWED_VALUE,
                    self::LANGUAGE_EN_ALLOWED_VALUE
                ]
            ]
        ];
    }
}


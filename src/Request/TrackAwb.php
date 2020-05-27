<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Request;

class TrackAwb extends Endpoint
{
    const DISPLAY_MODE_LAST_STATUS_ALLOWED_VALUE = 1; // last status
    const DISPLAY_MODE_LAST_RECORD_ALLOWED_VALUE = 2; // last record from dispatch history
    const DISPLAY_MODE_ALL_ALLOWED_VALUE = 3; // entire history
    const DISPLAY_MODE_CONFIRMATION_ALLOWED_VALUE = 4; // confirmation for received package
    const DISPLAY_MODE_JSON_ALLOWED_VALUE = 5; // JSON format
    const LANGUAGE_RO_ALLOWED_VALUE = 'ro';
    const LANGUAGE_EN_ALLOWED_VALUE = 'en';

    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'awb_tracking_integrat.php';
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
            'AWB' => [
                'required' => true
            ],
            'display_mode' => [
                'required' => true,
                'allowed_values' => [
                    self::DISPLAY_MODE_LAST_STATUS_ALLOWED_VALUE,
                    self::DISPLAY_MODE_LAST_RECORD_ALLOWED_VALUE,
                    self::DISPLAY_MODE_ALL_ALLOWED_VALUE,
                    self::DISPLAY_MODE_CONFIRMATION_ALLOWED_VALUE,
                    self::DISPLAY_MODE_JSON_ALLOWED_VALUE
                ]
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


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

class ExportReports extends Endpoint
{
    const LANGUAGE_RO_ALLOWED_VALUE = 'ro';
    const LANGUAGE_EN_ALLOWED_VALUE = 'en';

    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'export_raport_viramente_integrat.php';
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
     * @throws FanCourierInvalidParamException
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
            'data' => [ // order date, format dd.mm.yyyy
                'required' => true
            ],
            'language' => [
                'required' => false,
                'allowed_values' => [
                    self::LANGUAGE_RO_ALLOWED_VALUE,
                    self::LANGUAGE_EN_ALLOWED_VALUE
                ]
            ],
        ];
    }
}


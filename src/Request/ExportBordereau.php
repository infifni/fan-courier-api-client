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

class ExportBordereau extends Endpoint implements ReadCsvInterface
{
    const MODE_ONLY_SELFAWB_ALLOWED_VALUE = 0;
    const MODE_ALL_ALLOWED_VALUE = 1;
    const LANGUAGE_RO_ALLOWED_VALUE = 'ro';
    const LANGUAGE_EN_ALLOWED_VALUE = 'en';

    /**
     * @var array
     */
    private $csvReadParams;

    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'export_borderou_integrat.php';
    }

    /**
     * @return string
     */
    public function getApiResultType(): string
    {
        return EndpointInterface::API_RESULT_TYPE_CSV;
    }

    public function getReadCsvParams(): array
    {
        return $this->csvReadParams;
    }

    public function setReadCsvParams(array $params)
    {
        $this->csvReadParams = $params;
    }

    /**
     * @param array $params
     * @return boolean
     * @throws FanCourierInvalidParamException
     */
    public function validate(array $params): bool
    {
        return $this->validateAgainst($params, $this->getFieldRules());
    }

    /**
     * @return array
     */
    private function getFieldRules(): array
    {
        return [
            'data' => [ // bordereau date, format dd.mm.yyyy
                'required' => true
            ],
            'language' => [
                'required' => false,
                'allowed_values' => [
                    self::LANGUAGE_RO_ALLOWED_VALUE,
                    self::LANGUAGE_EN_ALLOWED_VALUE
                ]
            ],
            'mode' => [ // 0 - only dispatches from selfawb.ro; 1 - all dispatches
                'required' => false,
                'allowed_values' => [
                    self::MODE_ONLY_SELFAWB_ALLOWED_VALUE,
                    self::MODE_ALL_ALLOWED_VALUE
                ]
            ]
        ];
    }
}


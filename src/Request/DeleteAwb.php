<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Request;

class DeleteAwb extends Endpoint
{
    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'delete_awb_integrat.php';
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
     * @return int|string
     */
    public function parseResult($result)
    {
        if (false !== stripos($result, 'deleted')) {
            return (int) str_replace('deleted', '', strtolower($result));
        }

        return $result;
    }
    
    /**
     * @param array $params
     * @return boolean
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
            'AWB' => [
                'required' => true
            ]
        ];
    }
}


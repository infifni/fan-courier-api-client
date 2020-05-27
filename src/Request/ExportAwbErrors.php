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

class ExportAwbErrors extends Endpoint
{
    /**
     * @return string
     */
    protected function getApiPath(): string
    {
        return 'export_lista_erori_imp_awb_integrat.php';
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
        if (! empty($params)) {
            throw new FanCourierInvalidParamException('No fields required');
        }

        return true;
    }
}


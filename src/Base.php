<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient;

use Exception;
use Infifni\FanCourierApiClient\Exception\CsvWrongReadException;
use Infifni\FanCourierApiClient\Exception\FanCourierInstanceException;
use Infifni\FanCourierApiClient\Exception\FanCourierUnknownRequestException;
use Infifni\FanCourierApiClient\Exception\FanCourierInvalidParamException;
use \Curl\Curl;
use Infifni\FanCourierApiClient\Helper\Csv;
use Infifni\FanCourierApiClient\Request\CsvFileRequestInterface;
use Infifni\FanCourierApiClient\Request\EndpointInterface;
use Infifni\FanCourierApiClient\Request\ReadCsvInterface;

abstract class Base implements BaseInterface
{
    /** @var EndpointInterface */
    protected $instance;

    /**
     * 
     * @param string $class
     * @return EndpointInterface
     * @throws FanCourierUnknownRequestException
     */
    public function instantiate(string $class): EndpointInterface
    {
        $classCall = "Infifni\\FanCourierApiClient\\Request\\" . $class;
        if (! class_exists($classCall)) {
            throw new FanCourierUnknownRequestException("Class $classCall does not exist");
        }

        return new $classCall();
    }

    /**
     *
     * @param array $credentials
     * @return mixed
     * @throws FanCourierInvalidParamException
     * @throws FanCourierInstanceException
     */
    public function makeRequest(array $credentials)
    {
        if (! in_array($this->instance->getApiResultType(), $this->checkApiResultType(), true)) {
            throw new FanCourierInvalidParamException('Invalid result type');
        }

        $params = $this->instance->getRequestParams();

        if ($this->instance instanceof CsvFileRequestInterface) {
            $params = Csv::convertToCSV($params, $this->instance->getCsvHeaders());
        }

        $params = array_merge($params, $credentials);

        return $this->postCurlRequest(
            $params,
            $this->instance->getApiUrl(),
            $this->instance->getApiResultType()
        );
    }

    /**
     *
     * @param array $data
     * @param string $url
     * @param string $resultType
     * @return string
     * @throws FanCourierInstanceException
     * @throws Exception
     */
    private function postCurlRequest($data, $url, $resultType)
    {
        $curl = new Curl();
        $curl->setTimeout($this->timeout);
        $curl->post($url, $data);
        if ($curl->error) {
            throw new FanCourierInstanceException('Invalid curl error. Code: '. $curl->errorCode . '. Message: '. $curl->errorMessage);
        }

        return $this->getDataBasedOnType($resultType, $curl->response);
    }

    /**
     * 
     * @return array
     */
    private function checkApiResultType (): array
    {
        return [
            EndpointInterface::API_RESULT_TYPE_CSV,
            EndpointInterface::API_RESULT_TYPE_PLAIN,
            EndpointInterface::API_RESULT_TYPE_PARSE,
            EndpointInterface::API_RESULT_TYPE_HTML
        ];
    }

    /**
     *
     * @param string $type
     * @param string $result
     * @return string|bool|array
     * @throws CsvWrongReadException
     */
    private function getDataBasedOnType($type, $result)
    {
        switch ($type) {
            case EndpointInterface::API_RESULT_TYPE_CSV :
                if ($this->instance instanceof ReadCsvInterface) {
                    $csvParams = $this->instance->getReadCsvParams();

                    return Csv::csvStringToArray(
                        $result,
                        $csvParams['delimiter'],
                        $csvParams['enclosure'],
                        $csvParams['escape'],
                        $csvParams['return_type_format']
                    );
                }

                return Csv::csvStringToArray($result);
            case EndpointInterface::API_RESULT_TYPE_PARSE :
            case EndpointInterface::API_RESULT_TYPE_HTML :
                return is_callable([$this->instance, 'parseResult']) ? $this->instance->parseResult($result) : $result;
            default:
                return $result;
        }
    }

    /**
     * @param int $timeout
     * @return Base
     */
    abstract public function setTimeout(int $timeout): self;
}

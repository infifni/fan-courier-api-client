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
use Infifni\FanCourierApiClient\Exception\FanCourierInstanceException;
use Infifni\FanCourierApiClient\Exception\FanCourierInvalidParamException;
use Infifni\FanCourierApiClient\Exception\FanCourierUnknownRequestException;
use Infifni\FanCourierApiClient\Helper\Csv;
use Infifni\FanCourierApiClient\Request\ReadCsvInterface;

/**
 * @method generateAwb(array $params)
 * @method city(array $params = [])
 * @method deleteAwb(array $params)
 * @method getAwb(array $params)
 * @method trackAwb(array $params)
 * @method downloadAwb(array $params)
 * @method endBordereau(array $params = [])
 * @method exportBordereau(array $params)
 * @method exportAwbErrors(array $params = [])
 * @method exportObservations(array $params = [])
 * @method exportOrders(array $params)
 * @method exportServices(array $params = [])
 * @method exportReports(array $params)
 * @method order(array $params)
 * @method price(array $params)
 * @method streets(array $params = [])
 */
class Client extends Base
{
    /**
     * @var string[]
     */
    private $credentials;

    /**
     * @var integer
     */
    protected $timeout = 30;

    /**
     * @var array
     */
    private $defaultCsvReadParams = [
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '"',
        'return_type_format' => Csv::RETURN_TYPE_ARRAY_OF_ARRAYS
    ];

    /**
     * @var array
     */
    private $csvReadParams;

    /**
     * @param string $clientId
     * @param string $username
     * @param string $password
     */
    public function __construct(string $clientId, string $username, string $password)
    {
        $this->csvReadParams = $this->defaultCsvReadParams;
        $this->credentials = [
            'client_id' => $clientId,
            'user_pass'  => $password,
            'username'  => $username,
        ];
    }

    public function setCsvReadParams(array $params = []): self
    {
        $this->csvReadParams = array_merge($this->defaultCsvReadParams, $params);
        if (count(array_diff_key($this->defaultCsvReadParams, $this->csvReadParams))) {
            throw new FanCourierInvalidParamException(
                'The only allowed keys are: '.implode(',', array_keys($this->defaultCsvReadParams))
            );
        }

        return $this;
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws FanCourierUnknownRequestException
     * @throws FanCourierInstanceException
     */
    public function __call($method, $params = [])
    {
        $this->instance = $this->instantiate(ucfirst($method));
        call_user_func_array([$this->instance, 'initialize'], $params);

        if(! is_callable([$this->instance, 'initialize'])) {
            throw new FanCourierUnknownRequestException("Method $method does not exist");
        }

        try {
            if ($this->instance instanceof ReadCsvInterface) {
                $this->instance->setReadCsvParams($this->csvReadParams);
            }

            return $this->makeRequest($this->credentials);
        } catch (FanCourierInvalidParamException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new FanCourierInstanceException('Invalid request exception: '.$e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function setTimeout(int $timeout): Base
    {
        $this->timeout = $timeout;

        return $this;
    }
}

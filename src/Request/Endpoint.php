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

abstract class Endpoint implements EndpointInterface
{
    /**
     * @var array
     */
    public $requestParams;

    /**
     * @inheritDoc
     */
    abstract public function getApiResultType(): string;

    /**
     * @inheritDoc
     */
    abstract public function validate(array $params): bool;

    /**
     * @return string
     */
    abstract protected function getApiPath(): string;

    /**
     * @inheritDoc
     */
    public function getApiUrl(): string
    {
        return self::API_BASE_URL.$this->getApiPath();
    }

    /**
     * @inheritDoc
     */
    public function getRequestParams(): array
    {
        return $this->requestParams;
    }

    /**
     * @inheritDoc
     */
    public function setRequestParams(array $requestParams): EndpointInterface
    {
        $this->requestParams = $requestParams;

        return $this;
    }

    /**
     * @inheritDoc
     * @throws FanCourierInvalidParamException
     */
    public function initialize(array $params = []): EndpointInterface
    {
        if (! is_array($params)) {
            throw new FanCourierInvalidParamException('Require array');
        }

        $this->validate($params);

        return $this->setRequestParams($params);
    }

    /**
     * @param array $params
     * @param array $validationFieldRules
     * @param bool $rejectExtraFields
     * @return bool
     */
    protected function validateAgainst(array $params, array $validationFieldRules, bool $rejectExtraFields = true): bool
    {
        foreach ($validationFieldRules as $validationField => $rules) {
            if ($rules['required'] && (! isset($params[$validationField]) || '' === $params[$validationField])) {
                throw new FanCourierInvalidParamException("Field '$validationField' is required");
            }

            if (
                isset($params[$validationField], $rules['regex'])
                &&
                ! preg_match($rules['regex'], $params[$validationField])
            ) {
                throw new FanCourierInvalidParamException(
                    "Field '$validationField' does not match regex {$rules['regex']}"
                );
            }

            if (
                isset($params[$validationField], $rules['allowed_values'])
                &&
                ! in_array($params[$validationField], $rules['allowed_values'], true)
            ) {
                throw new FanCourierInvalidParamException(
                    "Field '$validationField' can have one of the following values: ".
                    implode(', ', $rules['allowed_values'])
                );
            }
        }

        if ($rejectExtraFields && count(array_diff_key($params, $validationFieldRules)) > 0) {
            throw new FanCourierInvalidParamException(
                'The only keys accepted are: '.implode(', ', array_keys($validationFieldRules))
            );
        }

        return true;
    }
}




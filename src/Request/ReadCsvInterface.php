<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Request;

interface ReadCsvInterface
{
    /**
     * @param array $params [
     *      'delimiter' => ',',
     *      'enclosure' => '"',
     *      'escape' => '"',
     *      'return_type_format' => \Infifni\FanCourierApiClient\Helper\Csv::RETURN_TYPE_ARRAY_OF_ARRAYS
     * ]
     * @return void
     */
    public function setReadCsvParams(array $params);

    /**
     * @return array $params [
     *      'delimiter' => ',',
     *      'enclosure' => '"',
     *      'escape' => '"',
     *      'return_type_format' => \Infifni\FanCourierApiClient\Helper\Csv::RETURN_TYPE_ARRAY_OF_ARRAYS
     * ]
     */
    public function getReadCsvParams(): array;
}
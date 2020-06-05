<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Helper;

use Exception;
use Infifni\FanCourierApiClient\Exception\CsvWrongReadException;
use Infifni\FanCourierApiClient\Exception\FanCourierInstanceException;
use CURLFile;

class Csv
{
    const RETURN_TYPE_ARRAY_OF_ARRAYS = 1;
    const RETURN_TYPE_ARRAY_OF_OBJECTS = 2;

    /**
     * @var resource
     */
    protected $fh;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @var string
     */
    private $enclosure;

    /**
     * @var string
     */
    private $escape;

    /**
     * @var int
     */
    private $returnTypeFormat;

    public function __construct(
        string $delimiter,
        string $enclosure,
        string $escape,
        int $returnTypeFormat = self::RETURN_TYPE_ARRAY_OF_ARRAYS
    ) {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->returnTypeFormat = $returnTypeFormat;
    }

    public function setFileHandler(string $string): self
    {
        $this->fh = fopen('data://text/plain;base64,' . base64_encode($string), 'rb');

        return $this;
    }

    public function __destruct()
    {
        fclose($this->fh);
    }

    /**
     * @return bool|array [
     *      'original' => 'string', // exactly what was read from the file
     *      'extracted' => 'array'  // the extracted array from the original CSV row
     * ]
     */
    protected function getCurrentRow()
    {
        $originalRow = fgets($this->fh);
        if (! $originalRow) {
            return false;
        }

        return [
            'original' => $originalRow,
            'extracted' => str_getcsv($originalRow, $this->delimiter, $this->enclosure, $this->escape)
        ];
    }

    public function setHeaders($headers)
    {
        array_walk($headers, [
            'self',
            'toUnderscoreCase'
        ]);
        $this->headers = $headers;
    }

    private static function toUnderscoreCase(string &$value)
    {
        $value = strtolower(str_replace(' ', '_', $value));
    }

    public function getHeaders(): array
    {
        if(! $this->headers) {
            $this->setHeaders($this->getCurrentRow()['extracted']);
        }

        return $this->headers;
    }

    public function reset()
    {
        $this->headers = null;
        rewind($this->fh);
    }

    /**
     * @param string $stringWithCsvData
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @param int $returnType
     * @return array
     * @throws CsvWrongReadException
     */
    public static function csvStringToArray(
        string $stringWithCsvData,
        string $delimiter = ',',
        string $enclosure = '"',
        string $escape = '\\',
        int $returnType = self::RETURN_TYPE_ARRAY_OF_ARRAYS
    ): array {
        $csv = new Csv($delimiter, $enclosure, $escape, $returnType);

        return $csv
            ->setFileHandler($stringWithCsvData)
            ->getRows();
    }

    /**
     * @return array
     * @throws CsvWrongReadException
     */
    public function getRows(): array
    {
        $this->reset();
        $headers  = $this->getHeaders();
        $nrHeaders = count($headers);
        $data = [];
        while($rowData = $this->getCurrentRow()) {
            if ($nrHeaders !== count($rowData['extracted'])) {
                throw new CsvWrongReadException(
                    "The number of headers do not coincide with the number of values from the current row.\n".
                    "Maybe it has something to do with the enclosure or the escape character used to read.\n".
                    'The row that was read is an array with the following contents: '.var_export($rowData['extracted'])."\n".
                    "The original row looks like this: {$rowData['original']}"
                );
            }

            $rowData = array_combine($headers, $rowData['extracted']);
            $data[] = self::RETURN_TYPE_ARRAY_OF_OBJECTS === $this->returnTypeFormat ? (object) $rowData : $rowData;
        }

        return $data;
    }

    /**
     * @param array $data
     * @param array $headers
     * @return array
     * @throws FanCourierInstanceException
     */
    public static function convertToCSV(array $data, array $headers): array
    {
        $return_data = [];
        $defaultRow = $headers;
        foreach ($defaultRow as $key => $value) {
            $defaultRow[$key] = '';
        }
        foreach ($data as $key => $value) {
            $filename = tempnam('/tmp', 'FanCourier'.  time(). '.csv');
            $csv = fopen($filename, 'wb');
            try {
                fputcsv($csv, $headers);
                foreach ($value as $row) {
                    fputcsv($csv, array_merge($defaultRow, $row));
                }
            } catch (Exception $exc) {
                throw new FanCourierInstanceException($exc->getTraceAsString());
            } finally {
                fclose($csv);
            }
            $return_data[$key] = new CURLFile($filename, 'text/csv');
        }

        return $return_data;
    }
}
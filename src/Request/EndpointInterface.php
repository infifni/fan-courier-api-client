<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Request;

interface EndpointInterface
{
    const API_BASE_URL = 'https://www.selfawb.ro/';

    const API_RESULT_TYPE_CSV = 'csv';
    const API_RESULT_TYPE_PLAIN = 'plain';
    const API_RESULT_TYPE_PARSE = 'parse';
    const API_RESULT_TYPE_HTML = 'html';

    const SERVICE_ALLOWED_VALUES = [
        'standard' => 'Standard', // cash money to the client
        'collector_account' => 'Cont Colector', // money in a bank account specified by client
        'red_code' => 'RedCode',
        'specifications' => 'Caiet Sarcini',
        'express_loco_one_hour' => 'Express Loco 1H',
        'express_loco_two_hours' => 'Express Loco 2H',
        'express_loco_four_hours' => 'Express Loco 4H',
        'express_loco_six_hours' => 'Express Loco 6H',
        'express_loco_one_hour_collector_account' => 'Express Loco 1H-Cont Colector',
        'express_loco_two_hours_collector_account' => 'Express Loco 2H-Cont Colector',
        'express_loco_four_hours_collector_account' => 'Express Loco 4H-Cont Colector',
        'express_loco_six_hours_collector_account' => 'Express Loco 6H-Cont Colector',
        'red_code_collector_account' => 'Red code-Cont Colector',
        'white_goods' => 'Produse Albe',
        'white_goods_collector_account' => 'Produse Albe-Cont Colector',
        'freight_transport' => 'Transport Marfa',
        'freight_transport_collector_account' => 'Transport Marfa-Cont Colector',
        'white_goods_freight_transport' => 'Transport Marfa Produse Albe',
        'white_goods_freight_transport_collector_account' => 'Transport Marfa Produse Albe-Cont Colector',
        'export' => 'export'
    ];

    const RECIPIENT_ALLOWED_VALUE = 'destinatar';
    const SENDER_ALLOWED_VALUE = 'expeditor';

    /**
     * @param array $params
     * @return EndpointInterface
     */
    public function initialize(array $params): EndpointInterface;

    /**
     * @param array $params
     * @return bool
     */
    public function validate(array $params): bool;

    /**
     * @return string
     */
    public function getApiResultType(): string;

    /**
     * @return string
     */
    public function getApiUrl(): string;

    /**
     * @return array
     */
    public function getRequestParams(): array;

    /**
     * @param array $requestParams
     * @return EndpointInterface
     */
    public function setRequestParams(array $requestParams): EndpointInterface;
}


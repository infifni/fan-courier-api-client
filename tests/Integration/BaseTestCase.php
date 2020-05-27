<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

namespace Infifni\FanCourierApiClient\Tests\Integration;

use Infifni\FanCourierApiClient\Client;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    const TEST_CLIENT_ID = '7032158';
    const TEST_USERNAME = 'clienttest';
    const TEST_PASSWORD = 'testing';

    /**
     * @var Client
     */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new Client(self::TEST_CLIENT_ID, self::TEST_USERNAME, self::TEST_PASSWORD);

        parent::setUp();
    }
}
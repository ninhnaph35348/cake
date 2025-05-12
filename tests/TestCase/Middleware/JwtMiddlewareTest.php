<?php
declare(strict_types=1);

namespace App\Test\TestCase\Middleware;

use App\Middleware\JwtMiddleware;
use Cake\TestSuite\TestCase;

/**
 * App\Middleware\JwtMiddleware Test Case
 */
class JwtMiddlewareTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Middleware\JwtMiddleware
     */
    protected $Jwt;

    /**
     * Test process method
     *
     * @return void
     * @uses \App\Middleware\JwtMiddleware::process()
     */
    public function testProcess(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

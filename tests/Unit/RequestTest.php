<?php

namespace Tests\Unit;

use App\Services\Api\Users\Request;
use App\Services\Api\Users\Response;
use App\Services\UsersService;
use Faker\Provider\Lorem;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RequestTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNoUrlAdded()
    {
        $this->expectException(\Exception::class);
        $usersRequest = new Request(1, '');
        $usersRequest->call();


    }

    public function testInvalidJsonProvided()
    {
        $this->expectException(\Exception::class);
        $usersRequest = new Request(1, '');
        $usersRequest->call();
    }

    public function testResponseIsEmpty()
    {
        $this->expectException(\Exception::class);
        $this->expectErrorMessage('No Data');
        Http::fake([
            'http://www.example.com?page=1' => Http::response([], 200),
        ]);

        $usersRequest = new Request(1, 'www.example.com');
        $usersRequest->call();
        $usersRequest->getResponseBody();
    }

    public function testMissingUserData()
    {
        $this->expectException(\Exception::class);
        $this->expectErrorMessage('User Failed Validation');
        $returnData = [
            [
                'id' => 1,
                'email' => Lorem::word() . '@' . 'gmail.com',
                'first_name' => Lorem::word(),
                'avatar' => "https://reqres.in/img/faces/1-image.jpg"
            ]
        ];
        Http::fake([
            'http://www.example.com?page=1' => Http::response([
                'data' => $returnData
            ], 200),
        ]);

        $usersRequest = new Request(1, 'http://www.example.com');
        $usersRequest->call();
        $response = $usersRequest->getResponse();
        $data = $response->getData();
        $this->assertEquals($returnData, $data);
        $usersService = new UsersService();
        $usersService->mapAndStore($data);
    }

    public function testWorksFine()
    {
        $returnData = [
            [
                'id' => 1,
                'email' => Lorem::word() . '@' . 'gmail.com',
                'first_name' => Lorem::word(),
                'last_name' => Lorem::word(),
                'avatar' => "https://reqres.in/img/faces/1-image.jpg"
            ]
        ];
        Http::fake([
            'http://www.example.com?page=1' => Http::response([
                'data' => $returnData
            ], 200),
        ]);

        $usersRequest = new Request(1, 'http://www.example.com');
        $usersRequest->call();
        $response = $usersRequest->getResponse();
        $data = $response->getData();
        $this->assertEquals($returnData, $data);
        $usersService = new UsersService();
        $usersService->mapAndStore($data);
    }
}

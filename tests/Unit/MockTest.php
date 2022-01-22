<?php

use Sammyjo20\Saloon\Clients\MockClient;
use Sammyjo20\Saloon\Http\MockResponse;
use Sammyjo20\Saloon\Tests\Resources\Requests\MockRequest;

test('pulling a response from the sequence will return the correct response', function () {
    $responseA = new MockResponse([], 200);
    $responseB = new MockResponse([], 201);
    $responseC = new MockResponse([], 500);

    $mockClient = new MockClient([$responseA, $responseB, $responseC]);

    expect($mockClient->getNextResponse()->getStatus())->toEqual($responseA->getStatus());
    expect($mockClient->getNextResponse()->getStatus())->toEqual($responseB->getStatus());
    expect($mockClient->getNextResponse()->getStatus())->toEqual($responseC->getStatus());
    expect($mockClient->isEmpty())->toBeTrue();
});

test('a mock response can be created from a request', function () {
    $request = new MockRequest;
    $response = MockResponse::fromRequest($request, 200);

    expect($response->getHeaders())->toEqual(array_merge($request->getHeaders(), ['Content-Type' => 'application/json']));
    expect($response->getConfig())->toEqual($request->getConfig());
    expect($response->getStatus())->toEqual(200);
});

test('a mock response can have raw body data', function () {
    $response = new MockResponse('xml', 200, ['Content-Type' => 'application/json']);

    expect($response->getHeaders())->toEqual(['Content-Type' => 'application/json']);
    expect($response->getConfig())->toEqual([]);
    expect($response->getStatus())->toEqual(200);
    expect($response->getFormattedData())->toEqual('xml');
});

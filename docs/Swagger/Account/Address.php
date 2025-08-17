<?php

namespace Docs\Swagger\Account;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/account/address',
    operationId: 'createAccountAddress',
    summary: '新增帳號地址',
    description: '新增帳號地址',
    tags: ['帳號地址'],
    security: [
        ['jwtToken' => []],
    ],
    requestBody: new OA\RequestBody(
        required: true,
        description: '帳號地址資料',
        content: new OA\JsonContent(
            required: ['address'],
            properties: [
                new OA\Property(
                    property: 'address',
                    type: 'string',
                    example: 'xx市xx區xx路xx號',
                    description: '地址',
                ),
            ],
        ),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: '新增帳號地址成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '新增帳號地址成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'accountAddressId',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'address',
                                type: 'string',
                                example: 'xx市xx區xx路xx號',
                            ),
                        ],
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 400,
            description: '新增帳號地址失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '新增帳號地址失敗，{錯誤資訊}',
                    ),
                ],
            ),
        ),
    ],
)]
#[OA\GET(
    path: '/api/account/address',
    operationId: 'getAccountAddressList',
    summary: '取得帳號地址列表',
    description: '取得帳號地址列表',
    tags: ['帳號地址'],
    security: [
        ['jwtToken' => []],
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: '取得帳號地址列表成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '取得帳號地址列表成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(
                                    property: 'accountAddressId',
                                    type: 'integer',
                                    example: 1,
                                ),
                                new OA\Property(
                                    property: 'address',
                                    type: 'string',
                                    example: 'xx市xx區xx路xx號',
                                ),
                            ],
                        ),
                        example: [
                            [
                                'accountAddressId' => 1,
                                'address' => 'xx市xx區xx路xx號',
                            ],
                            [
                                'accountAddressId' => 2,
                                'address' => 'xx市xx區xx路xx號',
                            ],
                        ],
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 400,
            description: '取得帳號地址列表失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '取得帳號地址列表失敗，{錯誤資訊}',
                    ),
                ],
            ),
        ),
    ],
)]
#[OA\DELETE(
    path: '/api/account/address/{accountAddressId}',
    operationId: 'deleteAccountAddress',
    summary: '刪除帳號地址',
    description: '刪除帳號地址',
    tags: ['帳號地址'],
    security: [
        ['jwtToken' => []],
    ],
    parameters: [
        new OA\Parameter(
            name: 'accountAddressId',
            description: '帳號地址ID',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer'),
            example: 1,
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: '刪除帳號地址成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '刪除帳號地址成功',
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 400,
            description: '刪除帳號地址失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '刪除帳號地址失敗，{錯誤資訊}',
                    ),
                ],
            ),
        ),
    ],
)]
class Address
{
}
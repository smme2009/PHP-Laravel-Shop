<?php

namespace Docs\Swagger\Account;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/account/profile',
    operationId: 'accountProfile',
    summary: '帳號資訊',
    description: '取得帳號資訊',
    tags: ['帳號'],
    security: [
        ['jwtToken' => []],
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: '取得帳號資料成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '取得帳號資料成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'account',
                                type: 'string',
                                example: 'test@test.com',
                            ),
                            new OA\Property(
                                property: 'name',
                                type: 'string',
                                example: '測試帳號',
                            ),
                        ],
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 400,
            description: '取得帳號資料失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '取得帳號資料失敗，{錯誤資訊}',
                    ),
                ],
            ),
        ),
    ],
)]
class Info
{
}
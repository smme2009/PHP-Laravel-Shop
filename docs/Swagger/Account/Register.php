<?php

namespace Docs\Swagger\Account;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/register',
    operationId: 'register',
    summary: '註冊',
    description: '帳號註冊',
    tags: ['帳號'],
    requestBody: new OA\RequestBody(
        required: true,
        description: '註冊資料',
        content: new OA\JsonContent(
            required: ['account', 'password', 'name'],
            properties: [
                new OA\Property(
                    property: 'account',
                    type: 'string',
                    example: 'test@test.com',
                    description: '帳號',
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    example: 'password',
                    description: '密碼',
                ),
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: '測試帳號',
                    description: '姓名',
                ),
            ],
        ),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: '註冊成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '註冊成功',
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
            description: '註冊失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '註冊失敗，{錯誤資訊}',
                    ),
                ],
            ),
        ),
    ],
)]
class Register
{
}
<?php

namespace Docs\Swagger\Account;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/login',
    operationId: 'login',
    summary: '登入',
    description: '帳號登入',
    tags: ['帳號'],
    requestBody: new OA\RequestBody(
        required: true,
        description: '登入資料',
        content: new OA\JsonContent(
            required: ['account', 'password', 'roleId'],
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
                    property: 'roleId',
                    type: 'integer',
                    example: 1,
                    description: '角色ID',
                ),
            ],
        ),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: '登入成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '登入成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'jwtToken',
                                type: 'string',
                                example: 'text.text.text',
                            ),
                        ],
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 400,
            description: '登入失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '登入失敗，{錯誤資訊}',
                    ),
                ],
            ),
        ),
    ],
)]
class Login
{
}
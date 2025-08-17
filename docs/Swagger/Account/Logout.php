<?php

namespace Docs\Swagger\Account;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/logout',
    operationId: 'logout',
    summary: '登出',
    description: '帳號登出',
    tags: ['帳號'],
    security: [
        ['jwtToken' => []],
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: '登出成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '登出成功',
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 400,
            description: '登出失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '登出失敗，{錯誤資訊}',
                    ),
                ],
            ),
        ),
    ],
)]
class Logout
{
}
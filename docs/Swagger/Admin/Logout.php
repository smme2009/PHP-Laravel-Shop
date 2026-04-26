<?php

namespace Docs\Swagger\Admin;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/mgmt/logout',
    operationId: 'adminLogout',
    summary: '管理員登出',
    description: '管理員登出',
    tags: ['管理員'],
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
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(),
                        example: [],
                    ),
                    new OA\Property(
                        property: 'errors',
                        type: 'array',
                        items: new OA\Items(),
                        example: [],
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 400,
            description: 'Jwt Token從白名單移除失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '登出失敗，Jwt Token無效',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(),
                        example: [],
                    ),
                    new OA\Property(
                        property: 'errors',
                        type: 'array',
                        items: new OA\Items(),
                        example: [],
                    ),
                ],
            ),
        ),
    ],
)]
class Logout {}

<?php

namespace Docs\Swagger\Banner;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/mgmt/banner',
    operationId: 'bannerCreate',
    summary: '新增橫幅',
    description: '新增橫幅',
    tags: ['橫幅'],
    security: [
        ['jwtToken' => []],
    ],
    requestBody: new OA\RequestBody(
        required: true,
        description: '橫幅資料',
        content: new OA\JsonContent(
            required: ['name', 'photoFileId', 'startAt', 'endAt', 'sort', 'status'],
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: '測試橫幅',
                    description: '橫幅名稱',
                ),
                new OA\Property(
                    property: 'photoFileId',
                    type: 'integer',
                    example: 1,
                    description: '圖片檔案ID',
                ),
                new OA\Property(
                    property: 'url',
                    type: 'string',
                    nullable: true,
                    example: 'https://example.com/test',
                    description: '點擊連結網址',
                ),
                new OA\Property(
                    property: 'startAt',
                    type: 'string',
                    format: 'date',
                    example: '2024-06-01',
                    description: '上架開始日期',
                ),
                new OA\Property(
                    property: 'endAt',
                    type: 'string',
                    format: 'date',
                    example: '2024-08-31',
                    description: '上架結束日期',
                ),
                new OA\Property(
                    property: 'sort',
                    type: 'integer',
                    minimum: 1,
                    maximum: 100,
                    example: 1,
                    description: '排序（1～100）',
                ),
                new OA\Property(
                    property: 'status',
                    type: 'boolean',
                    example: true,
                    description: '啟用狀態',
                ),
            ],
        ),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: '新增橫幅成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '新增橫幅成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'bannerId',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'photoFileId',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'name',
                                type: 'string',
                                example: '測試橫幅',
                            ),
                            new OA\Property(
                                property: 'url',
                                type: 'string',
                                example: 'https://example.com/test',
                            ),
                            new OA\Property(
                                property: 'startAt',
                                type: 'string',
                                example: '2024-06-01',
                            ),
                            new OA\Property(
                                property: 'endAt',
                                type: 'string',
                                example: '2024-08-31',
                            ),
                            new OA\Property(
                                property: 'sort',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'status',
                                type: 'boolean',
                                example: true,
                            ),
                            new OA\Property(
                                property: 'photoUrl',
                                type: 'string',
                                example: 'https://example.com/public/banner/test.jpg',
                            ),
                        ],
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
            response: 422,
            description: '欄位驗證失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '新增橫幅失敗，欄位填寫錯誤',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(),
                        example: [],
                    ),
                    new OA\Property(
                        property: 'errors',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'name',
                                type: 'array',
                                items: new OA\Items(type: 'string'),
                                example: ['橫幅名稱為必填'],
                            ),
                            new OA\Property(
                                property: 'photoFileId',
                                type: 'array',
                                items: new OA\Items(type: 'string'),
                                example: ['圖片檔案ID為必填'],
                            ),
                            new OA\Property(
                                property: 'startAt',
                                type: 'array',
                                items: new OA\Items(type: 'string'),
                                example: ['上架開始日期為必填'],
                            ),
                            new OA\Property(
                                property: 'endAt',
                                type: 'array',
                                items: new OA\Items(type: 'string'),
                                example: ['上架結束日期為必填'],
                            ),
                            new OA\Property(
                                property: 'sort',
                                type: 'array',
                                items: new OA\Items(type: 'string'),
                                example: ['排序為必填'],
                            ),
                            new OA\Property(
                                property: 'status',
                                type: 'array',
                                items: new OA\Items(type: 'string'),
                                example: ['啟用狀態為必填'],
                            ),
                        ],
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 500,
            description: '系統異常',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '新增橫幅失敗，系統異常',
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
#[OA\Get(
    path: '/api/mgmt/banner',
    operationId: 'bannerGetPaged',
    summary: '取得橫幅分頁',
    description: '取得橫幅分頁',
    tags: ['橫幅'],
    security: [
        ['jwtToken' => []],
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: '取得橫幅分頁成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '取得橫幅分頁成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'bannerId',
                                    type: 'integer',
                                    example: 1,
                                ),
                                new OA\Property(
                                    property: 'photoFileId',
                                    type: 'integer',
                                    example: 1,
                                ),
                                new OA\Property(
                                    property: 'name',
                                    type: 'string',
                                    example: '測試橫幅',
                                ),
                                new OA\Property(
                                    property: 'url',
                                    type: 'string',
                                    example: 'https://example.com/test',
                                ),
                                new OA\Property(
                                    property: 'startAt',
                                    type: 'string',
                                    example: '2024-06-01',
                                ),
                                new OA\Property(
                                    property: 'endAt',
                                    type: 'string',
                                    example: '2024-08-31',
                                ),
                                new OA\Property(
                                    property: 'sort',
                                    type: 'integer',
                                    example: 1,
                                ),
                                new OA\Property(
                                    property: 'status',
                                    type: 'boolean',
                                    example: true,
                                ),
                                new OA\Property(
                                    property: 'photoUrl',
                                    type: 'string',
                                    example: 'https://example.com/public/banner/test.jpg',
                                ),
                            ],
                        ),
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
#[OA\Get(
    path: '/api/mgmt/banner/{bannerId}',
    operationId: 'bannerGet',
    summary: '取得橫幅',
    description: '取得橫幅',
    tags: ['橫幅'],
    security: [
        ['jwtToken' => []],
    ],
    parameters: [
        new OA\Parameter(
            name: 'bannerId',
            in: 'path',
            required: true,
            description: '橫幅ID',
            schema: new OA\Schema(type: 'integer', example: 1),
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: '取得橫幅成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '取得橫幅成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'bannerId',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'photoFileId',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'name',
                                type: 'string',
                                example: '測試橫幅',
                            ),
                            new OA\Property(
                                property: 'url',
                                type: 'string',
                                example: 'https://example.com/test',
                            ),
                            new OA\Property(
                                property: 'startAt',
                                type: 'string',
                                example: '2024-06-01',
                            ),
                            new OA\Property(
                                property: 'endAt',
                                type: 'string',
                                example: '2024-08-31',
                            ),
                            new OA\Property(
                                property: 'sort',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'status',
                                type: 'boolean',
                                example: true,
                            ),
                            new OA\Property(
                                property: 'photoUrl',
                                type: 'string',
                                example: 'https://example.com/public/banner/test.jpg',
                            ),
                        ],
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
            response: 404,
            description: '橫幅不存在',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '取得橫幅失敗，橫幅不存在',
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
#[OA\Put(
    path: '/api/mgmt/banner/{bannerId}',
    operationId: 'bannerModify',
    summary: '修改橫幅',
    description: '修改橫幅',
    tags: ['橫幅'],
    security: [
        ['jwtToken' => []],
    ],
    parameters: [
        new OA\Parameter(
            name: 'bannerId',
            in: 'path',
            required: true,
            description: '橫幅ID',
            schema: new OA\Schema(type: 'integer', example: 1),
        ),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        description: '橫幅資料',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: '測試橫幅',
                    description: '橫幅名稱',
                ),
                new OA\Property(
                    property: 'photoFileId',
                    type: 'integer',
                    example: 1,
                    description: '圖片檔案ID',
                ),
                new OA\Property(
                    property: 'url',
                    type: 'string',
                    nullable: true,
                    example: 'https://example.com/test',
                    description: '點擊連結網址',
                ),
                new OA\Property(
                    property: 'startAt',
                    type: 'string',
                    format: 'date',
                    example: '2024-06-01',
                    description: '上架開始日期',
                ),
                new OA\Property(
                    property: 'endAt',
                    type: 'string',
                    format: 'date',
                    example: '2024-08-31',
                    description: '上架結束日期',
                ),
                new OA\Property(
                    property: 'sort',
                    type: 'integer',
                    minimum: 1,
                    maximum: 100,
                    example: 1,
                    description: '排序（1～100）',
                ),
                new OA\Property(
                    property: 'status',
                    type: 'boolean',
                    example: true,
                    description: '啟用狀態',
                ),
            ],
        ),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: '修改橫幅成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '修改橫幅成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'bannerId',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'photoFileId',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'name',
                                type: 'string',
                                example: '測試橫幅',
                            ),
                            new OA\Property(
                                property: 'url',
                                type: 'string',
                                example: 'https://example.com/test',
                            ),
                            new OA\Property(
                                property: 'startAt',
                                type: 'string',
                                example: '2024-06-01',
                            ),
                            new OA\Property(
                                property: 'endAt',
                                type: 'string',
                                example: '2024-08-31',
                            ),
                            new OA\Property(
                                property: 'sort',
                                type: 'integer',
                                example: 1,
                            ),
                            new OA\Property(
                                property: 'status',
                                type: 'boolean',
                                example: true,
                            ),
                            new OA\Property(
                                property: 'photoUrl',
                                type: 'string',
                                example: 'https://example.com/public/banner/test.jpg',
                            ),
                        ],
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
            response: 404,
            description: '橫幅不存在',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '修改橫幅失敗，橫幅不存在',
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
            response: 500,
            description: '系統異常',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '修改橫幅失敗，系統異常',
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
#[OA\Delete(
    path: '/api/mgmt/banner/{bannerId}',
    operationId: 'bannerRemove',
    summary: '移除橫幅',
    description: '移除橫幅',
    tags: ['橫幅'],
    security: [
        ['jwtToken' => []],
    ],
    parameters: [
        new OA\Parameter(
            name: 'bannerId',
            in: 'path',
            required: true,
            description: '橫幅ID',
            schema: new OA\Schema(type: 'integer', example: 1),
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: '移除橫幅成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '移除橫幅成功',
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
            response: 404,
            description: '橫幅不存在',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '移除橫幅失敗，橫幅不存在',
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
            response: 500,
            description: '系統異常',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '移除橫幅失敗，系統異常',
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
class Banner {}
<?php

namespace Docs\Swagger\Admin;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/mgmt/login',
    operationId: 'adminLogin',
    summary: '管理員登入',
    description: '管理員登入',
    tags: ['管理員'],
    requestBody: new OA\RequestBody(
        required: true,
        description: '登入資料',
        content: new OA\JsonContent(
            required: ['account', 'password'],
            properties: [
                new OA\Property(
                    property: 'account',
                    type: 'string',
                    example: 'testadmin',
                    description: '帳號',
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    example: 'testpassword',
                    description: '密碼',
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
            description: '帳號或密碼錯誤',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '登入失敗，帳號或密碼錯誤',
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
            response: 422,
            description: '欄位驗證失敗',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '登入失敗，欄位填寫錯誤',
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
                                property: 'account',
                                type: 'array',
                                items: new OA\Items(type: 'string'),
                                example: ['帳號為必填', '帳號長度須介於8至12個字元'],
                            ),
                            new OA\Property(
                                property: 'password',
                                type: 'array',
                                items: new OA\Items(type: 'string'),
                                example: ['密碼為必填', '密碼長度須介於8至12個字元'],
                            ),
                        ],
                    ),
                ],
            ),
        ),
        new OA\Response(
            response: 500,
            description: '取得JWT Token時發生異常',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '登入失敗，取得JWT Token時發生異常',
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
class Login {}

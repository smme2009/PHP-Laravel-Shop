<?php

namespace Docs\Swagger\Banner;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/mgmt/banner/photo',
    operationId: 'bannerUploadPhoto',
    summary: '上傳橫幅圖片',
    description: '上傳橫幅圖片',
    tags: ['橫幅'],
    security: [
        ['jwtToken' => []],
    ],
    requestBody: new OA\RequestBody(
        required: true,
        description: '圖片檔案',
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['photo'],
                properties: [
                    new OA\Property(
                        property: 'photo',
                        type: 'string',
                        format: 'binary',
                        description: '橫幅圖片',
                    ),
                ],
            ),
        ),
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: '上傳橫幅圖片成功',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '上傳橫幅圖片成功',
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'photoFileId',
                                type: 'integer',
                                example: 1,
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
            description: '圖片格式不符',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: '上傳橫幅圖片失敗，圖片格式不符',
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
                        example: '上傳橫幅圖片失敗，系統異常',
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
class File {}
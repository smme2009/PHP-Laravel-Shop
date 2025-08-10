<?php

namespace Docs\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "商城 API",
    version: "2.0.0"
)]
#[OA\Server(
    url: "http://localhost:8000"
)]
class Api
{
}
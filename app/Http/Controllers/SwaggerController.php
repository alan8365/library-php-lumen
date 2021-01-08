<?php

namespace App\Http\Controllers;

use OpenApi\Annotations\Contact;
use OpenApi\Annotations\Info;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Server;

/**
 * @info(
 *     version="1.0.0",
 *     title="Library",
 *     description="A php library website",
 *     @Contact(
 *         email="alan8365@gmail.com",
 *         name="Lucy"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="Bearer",
 *      bearerFormat="JWT",
 * )
 *
 * @Server(
 *     url="http://localhost:8000",
 *     description="Develop environment",
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="Auth endpoints",
 * )
 *
 * @OA\Tag(
 *     name="Book",
 *     description="Auth endpoints",
 * )
 *
 * @package App\Http\Controllers
 */
class SwaggerController
{
}

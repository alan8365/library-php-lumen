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
 *     description="HI",
 *     @Contact(
 *         email="alan8365@gmail.com",
 *         name="Lucy"
 *     )
 * )
 *
 * @Server(
 *     url="http://localhost",
 *     description="dev env",
 * )
 *
 * @Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     description="響應實體，響應結果統一使用該結構",
 *     title="響應實體",
 *     @Property(
 *         property="code",
 *         type="string",
 *         description="響應程式碼"
 *     ),
 *     @Property(property="message", type="string", description="響應結果提示")
 * )
 *
 * @package App\Http\Controllers
 */
class SwaggerController
{
}

<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Book Manager API",
 *     version="1.0.0",
 *     description="API Documentation for Book Management System",
 *     @OA\Contact(
 *         email="zuldev@example.com",
 *         name="ZulDev"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */

abstract class Controller
{
    //
}

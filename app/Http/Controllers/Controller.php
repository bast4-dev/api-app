<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Books API",
    version: "1.0.0",
    description: "API REST pour la gestion de livres avec authentification"
)]
#[OA\Server(
    url: "http://localhost:8000/api/v1",
    description: "Serveur de développement"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer"
)]
abstract class Controller
{
    //
}

<?php

namespace App\Http\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "RegisterRequest",
    required: ["name", "email", "password"],
    properties: [
        new OA\Property(property: "name", type: "string", maxLength: 255, example: "John Doe"),
        new OA\Property(property: "email", type: "string", format: "email", maxLength: 255, example: "john@example.com"),
        new OA\Property(property: "password", type: "string", format: "password", minLength: 8, example: "password123")
    ]
)]
#[OA\Schema(
    schema: "LoginRequest",
    required: ["email", "password"],
    properties: [
        new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
        new OA\Property(property: "password", type: "string", format: "password", example: "password123")
    ]
)]
#[OA\Schema(
    schema: "UserResponse",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "John Doe"),
        new OA\Property(property: "email", type: "string", example: "john@example.com")
    ]
)]
#[OA\Schema(
    schema: "RegisterResponse",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Inscription réussie."),
        new OA\Property(property: "user", ref: "#/components/schemas/UserResponse")
    ]
)]
#[OA\Schema(
    schema: "LoginResponse",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Connexion réussie."),
        new OA\Property(property: "user", ref: "#/components/schemas/UserResponse"),
        new OA\Property(property: "token", type: "string", example: "1|abcdef123456...")
    ]
)]
#[OA\Schema(
    schema: "LogoutResponse",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Déconnexion réussie.")
    ]
)]
class UserSchema
{
}

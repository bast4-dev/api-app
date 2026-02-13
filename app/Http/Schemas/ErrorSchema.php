<?php

namespace App\Http\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Error",
    properties: [
        new OA\Property(
            property: "message", 
            type: "string", 
            example: "Une erreur est survenue"
        )
    ]
)]
#[OA\Schema(
    schema: "UnauthenticatedError",
    properties: [
        new OA\Property(
            property: "message", 
            type: "string", 
            example: "Unauthenticated."
        )
    ]
)]
#[OA\Schema(
    schema: "NotFoundError",
    properties: [
        new OA\Property(
            property: "message", 
            type: "string", 
            example: "No query results for model [App\\Models\\Book]."
        )
    ]
)]
#[OA\Schema(
    schema: "ValidationError",
    properties: [
        new OA\Property(property: "message", type: "string", example: "The given data was invalid."),
        new OA\Property(
            property: "errors",
            type: "object",
            example: ["field" => ["The field is required."]]
        )
    ]
)]
#[OA\Schema(
    schema: "InvalidCredentialsError",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Identifiants incorrects.")
    ]
)]
#[OA\Schema(
    schema: "TooManyAttemptsError",
    properties: [
        new OA\Property(property: "message", type: "string", example: "Too Many Attempts.")
    ]
)]
class ErrorSchema
{
}

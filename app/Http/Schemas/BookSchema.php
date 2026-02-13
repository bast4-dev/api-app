<?php

namespace App\Http\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Book",
    required: ["title", "author", "summary", "isbn"],
    properties: [
        new OA\Property(property: "title", type: "string", minLength: 3, maxLength: 255, example: "1984"),
        new OA\Property(property: "author", type: "string", minLength: 3, maxLength: 100, example: "GEORGE ORWELL"),
        new OA\Property(property: "summary", type: "string", minLength: 10, maxLength: 500, example: "Roman dystopique décrivant une société totalitaire contrôlée par Big Brother."),
        new OA\Property(property: "isbn", type: "string", minLength: 13, maxLength: 13, example: "9780451524935")
    ]
)]
class BookSchema
{
}

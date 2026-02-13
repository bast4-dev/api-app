<?php

namespace App\Http\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "BookResource",
    properties: [
        new OA\Property(
            property: "data",
            properties: [
                new OA\Property(property: "title", type: "string", example: "1984"),
                new OA\Property(property: "author", type: "string", example: "GEORGE ORWELL"),
                new OA\Property(property: "summary", type: "string", example: "Roman dystopique..."),
                new OA\Property(property: "isbn", type: "string", example: "9780451524935"),
                new OA\Property(
                    property: "_links",
                    properties: [
                        new OA\Property(
                            property: "self",
                            properties: [
                                new OA\Property(property: "href", type: "string", example: "http://localhost:8000/api/v1/books/1")
                            ],
                            type: "object"
                        ),
                        new OA\Property(
                            property: "update",
                            properties: [
                                new OA\Property(property: "href", type: "string", example: "http://localhost:8000/api/v1/books/1")
                            ],
                            type: "object"
                        ),
                        new OA\Property(
                            property: "delete",
                            properties: [
                                new OA\Property(property: "href", type: "string", example: "http://localhost:8000/api/v1/books/1")
                            ],
                            type: "object"
                        ),
                        new OA\Property(
                            property: "all",
                            properties: [
                                new OA\Property(property: "href", type: "string", example: "http://localhost:8000/api/v1/books")
                            ],
                            type: "object"
                        )
                    ],
                    type: "object"
                )
            ],
            type: "object"
        )
    ]
)]
class BookResourceSchema
{
}

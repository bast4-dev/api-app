<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class BookController extends Controller
{
    #[OA\Get(
        path: '/books',
        summary: 'Liste paginée des livres',
        description: 'Retourne la liste des livres avec pagination (2 livres par page)',
        tags: ['Livres'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json')
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Numéro de page',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des livres récupérée avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/BookResource')),
                        new OA\Property(property: 'links', type: 'object'),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer')
                            ],
                            type: 'object'
                        )
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        return BookResource::collection(Book::paginate(2));
    }

    #[OA\Post(
        path: '/books',
        summary: 'Créer un nouveau livre',
        description: 'Crée un nouveau livre dans la base de données (authentification requise)',
        security: [['bearerAuth' => []]],
        tags: ['Livres'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json')
            ),
            new OA\Parameter(
                name: 'Authorization',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'Bearer {token}')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/Book')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Livre créé avec succès',
                content: new OA\JsonContent(ref: '#/components/schemas/BookResource')
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedError')
            ),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            )
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:100',
            'summary' => 'required|string|min:10|max:500',
            'isbn' => 'required|string|size:13|unique:books,isbn',
        ]);

        $book = Book::create($validated);

        return new BookResource($book);
    }

    #[OA\Get(
        path: '/books/{id}',
        summary: 'Afficher un livre spécifique',
        description: 'Retourne les détails d\'un livre (avec mise en cache pendant 60 minutes)',
        tags: ['Livres'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json')
            ),
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID du livre',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Détails du livre',
                content: new OA\JsonContent(ref: '#/components/schemas/BookResource')
            ),
            new OA\Response(
                response: 404,
                description: 'Livre non trouvé',
                content: new OA\JsonContent(ref: '#/components/schemas/NotFoundError')
            )
        ]
    )]
    public function show(Book $book)
    {
        $cachedBook = Cache::remember("book.{$book->id}", 3600, function () use ($book) {
            return $book;
        });

        return new BookResource($cachedBook);
    }

    #[OA\Put(
        path: '/books/{id}',
        summary: 'Modifier un livre',
        description: 'Met à jour les informations d\'un livre existant (authentification requise)',
        security: [['bearerAuth' => []]],
        tags: ['Livres'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json')
            ),
            new OA\Parameter(
                name: 'Authorization',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'Bearer {token}')
            ),
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID du livre',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/Book')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Livre modifié avec succès',
                content: new OA\JsonContent(ref: '#/components/schemas/BookResource')
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedError')
            ),
            new OA\Response(
                response: 404,
                description: 'Livre non trouvé',
                content: new OA\JsonContent(ref: '#/components/schemas/NotFoundError')
            ),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            )
        ]
    )]
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:100',
            'summary' => 'required|string|min:10|max:500',
            'isbn' => 'required|string|size:13|unique:books,isbn,' . $book->id,
        ]);

        $book->update($validated);
        Cache::forget("book.{$book->id}");

        return new BookResource($book);
    }

    #[OA\Delete(
        path: '/books/{id}',
        summary: 'Supprimer un livre',
        description: 'Supprime un livre de la base de données (authentification requise)',
        security: [['bearerAuth' => []]],
        tags: ['Livres'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', default: 'application/json')
            ),
            new OA\Parameter(
                name: 'Authorization',
                in: 'header',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'Bearer {token}')
            ),
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID du livre',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Livre supprimé avec succès'
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié',
                content: new OA\JsonContent(ref: '#/components/schemas/UnauthenticatedError')
            ),
            new OA\Response(
                response: 404,
                description: 'Livre non trouvé',
                content: new OA\JsonContent(ref: '#/components/schemas/NotFoundError')
            )
        ]
    )]
    public function destroy(Book $book)
    {
        Cache::forget("book.{$book->id}");
        $book->delete();

        return response()->noContent();
    }
}
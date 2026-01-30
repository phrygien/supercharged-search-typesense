<?php

use Illuminate\Support\Facades\Route;

use Typesense\Client;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/create-collection', function () {
    $client = new Client([
        'api_key' => config('services.typesense.api_key'),
        'nodes' => [
            [
                'host' => config('services.typesense.host'),
                'port' => config('services.typesense.port'),
                'protocol' => config('services.typesense.protocol'),
            ],
        ],
        'connection_timeout' => 2,
    ]);

    $bookSchema = [
        'name' => 'books',
        'fields' => [
            // title, author, publication_year, ratings_count, average_rating
            ['name' => 'title', 'type' => 'string'],
            ['name' => 'authors', 'type' => 'string[]'],
            ['name' => 'publication_year', 'type' => 'int32'],
            ['name' => 'ratings_count', 'type' => 'int32'],
            ['name' => 'average_rating', 'type' => 'float'],
        ],
        'default_sorting_field' => 'ratings_count',
    ];

    // delete existing collection before
    $client->collections['books']->delete();

    // create collections bookschema
    $client->collections->create($bookSchema);

    return 'Collection created successfully';
});

Route::get('/import-collection', function () {
    $client = new Client([
        'api_key' => config('services.typesense.api_key'),
        'nodes' => [
            [
                'host' => config('services.typesense.host'),
                'port' => config('services.typesense.port'),
                'protocol' => config('services.typesense.protocol'),
            ],
        ],
        'connection_timeout' => 2,
    ]);

    $books = file_get_contents(base_path('books.jsonl'));

    $response = $client->collections['books']->documents->import($books);

    return 'Books imported successfully';
});

Route::get('/searching-collection', function () {
    $client = new Client([
        'api_key' => config('services.typesense.api_key'),
        'nodes' => [
            [
                'host' => config('services.typesense.host'),
                'port' => config('services.typesense.port'),
                'protocol' => config('services.typesense.protocol'),
            ],
        ],
        'connection_timeout' => 2,
    ]);

    $results = $client->collections['books']->documents->search([
        'q' => request('q'),
        'query_by' => 'title',
        //'sort_by' => '_text_match:desc, ratings_count:desc',
        'sort_by' => '_text_match:desc',
        'per_page' => 3,
    ]);

    $title = collect($results['hits'])->map(fn($shit) => $shit['document']['title']);

    return $title;
});

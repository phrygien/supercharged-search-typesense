<?php

use Illuminate\Support\Facades\Route;

use Typesense\Client;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/search', function (Client $client) {
    $query = request('q', '*');

    $results = $client->collections['books']->documents->search([
        'q' => $query,
        'query_by' => 'title'
    ]);

    $results = collect($results['hits'])->pluck('document.title'); // Extract documents from the data

    return view('search', [
        'results' => $results
    ]);
});

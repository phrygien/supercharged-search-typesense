<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Search Books</title>
</head>
<body class="p-8">
    <form action="/search" method="GET">
        <input type="search" name="q" class="border border-gray-300 border-b-0 w-full py-2 px-4" value="{{ request('q') }}">
    </form>

    <ul class="border border-gray-300 py-2 px-4">
        @forelse($results as $result)
            <li>{!! $result !!}</li>
        @empty
            <li>No Matching results.</li>
        @endforelse
    </ul>
</body>
</html>

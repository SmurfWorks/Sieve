<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sieve - Data filtering</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    </head>

    <body class="bg-gray-100" style="font-family:'Open Sans', sans-serif;">

        <main></main>

        <style>
            select, input[type="text"] {
                background: white;
                border: solid 1px rgb(229, 231, 235);
                line-height: 1;
                padding: 0.5rem;
                font-size: 90%;
                width: 100%;
            }
        </style>

        <script>
            window.schema = @json($schema)
        </script>
        <script src="/vendor/sieve/dist/sieve.iife.js"></script>
    </body>
</html>

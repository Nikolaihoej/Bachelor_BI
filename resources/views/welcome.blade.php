<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body>
        <h1>Users</h1>
        <ul>
            @foreach ($users as $user)
                <li>{{ $user->id }} -- {{ $user->first_name }} - {{ $user->last_name }} - {{ $user->email }} - {{ $user->created_at }}</li>
            @endforeach
        </ul>
    </body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

    </style>

</head>
<body>
    <h1>Holiday: {{ $title }}</h1>
    <p><b>Description:</b></p>
    <p>{{ $description }}</p>
    <p><b>Location:</b></p>
    <p>{{ $location }}</p>
    <p><b>Date:</b></p>
    <p>{{ $date }}</p>

    <br />
    <p><b>Participants:</b></p>
    <table>
        <thead>
            <th>Name</th>
            <th>Last name</th>
            <th>E-mail</th>
         </thead>
         <tbody>
            @foreach ( $participants as $itemI)
            <tr>
                <td>{{$itemI->name}}</td>
                <td>{{$itemI->last_name}}</td>
                <td>{{$itemI->email}}</td>
            </tr>
            @endforeach

        </tbody>
    </table>

</body>
</html>

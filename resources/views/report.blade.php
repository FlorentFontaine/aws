@include('nav')
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Logs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .log-message {
            max-width: 700px;
            white-space: pre-wrap; /* Permet de faire un retour à la ligne */
        }
        .full-screen-container {
            height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .centered-image {
            width: 70%;
        }
    </style>
</head>
<body>
@if(!empty($logs))
    <h1 class="m-2 text-danger text-center pb-4 shadow-lg">Erreurs Fatales</h1>
    <div class="m-4">
        <table class="table mt-4" id="volumesTable">
            <thead class="table-light">
            <tr>
                <th>Log Name</th>
                <th>Date</th>
                <th>Message</th>
                <th>Referer</th>
            </tr>
            </thead>
            <tbody>
            <tbody>
            @php
                $logCounts = [];
            @endphp
            @foreach($logs as $log)
                @php
                    $bgColor = $loop->iteration % 2 ? '#FFF' : '#EEE';
                    $message = $log['message'];

                    if (isset($logCounts[$message])) {
                        $logCounts[$message]['count']++;
                    } else {
                        $logCounts[$message] = ['count' => 1, 'log' => $log];
                    }
                @endphp
            @endforeach
            @foreach($logCounts as $message => $data)
                @php
                    $log = $data['log'];
                    $count = $data['count'];
                    $bgColor = $loop->iteration % 2 ? '#FFF' : '#EEE';
                @endphp
                <tr style="background-color: {{ $bgColor }}">
                    <td>{{ $log['logname'] }}</td>
                    <td>{{ $log['date'] }}</td>
                    <td>{{ $count }} fois ce message</td>
                    <td><pre class="log-message">{{ $log['message'] }}</pre></td>
                    <td>{{ $log['referer'] }}</td>
                </tr>
            @endforeach
        </table>
        @include('partials.ticketRedmine')
    </div>
@else
    <h1 class="m-2  text-center pb-4 shadow-lg">Aucune Erreur Fatal</h1>
    <div class="full-screen-container">
        <img class="centered-image" src="{{ asset('images/perfect.jpg') }}" alt="pas d'erreur">
    </div>
@endif

</body>
</html>

<!-- resources/views/report.blade.php -->
@include('nav')
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Logs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<h1 class="mb-5 bg-dark text-center text-white bg-secondary pb-4 shadow-lg">Logs Fatal</h1>
    @if(!empty($logs))
    <div class="m-4">
        <table class="table mt-4" id="volumesTable">
            <thead class="table-light">
            </thead>
            <tbody>
            </tbody>
        </table>
    @else
        <p>Aucun log Fatal trouvé.</p>
    @endif
    </div>
</body>
</html>

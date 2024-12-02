<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWS</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">AWS Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="tagsDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Gestion des Tags
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="tagsDropdown">
                        <li><a class="dropdown-item" href='{{ route("ecdeuxinstance") }}'>Instances EC2</a></li>
                        <li><a class="dropdown-item" href='{{ route("ecdeuxvolume") }}'>Volumes EC2</a></li>
                        <li><a class="dropdown-item" href='{{ route("rds") }}'>RDS</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="errorsDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Gestion des Erreurs PROD Report
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="errorsDropdown">
                        <li><a class="dropdown-item"
                               href="{{ route('report', ['arn' => 'arn:aws:logs:eu-west-3:082157043056:log-group:total-applications', 'identifier' => 'total-pilot']) }}">Pilot</a>
                        </li>
                        <li><a class="dropdown-item"
                               href="{{ route('report',  ['arn' => 'arn:aws:logs:eu-west-3:082157043056:log-group:total-applications', 'identifier' => 'total-optifi']) }}">Optifi</a>
                        </li>
                        <li><a class="dropdown-item"
                               href="{{ route('report',  ['arn' => 'arn:aws:logs:eu-west-3:082157043056:log-group:total-applications', 'identifier' => 'total-comete']) }}">Comete</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="errorsDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Gestion des Erreurs PREVIEW Report
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="errorsDropdown">
                        <li><a class="dropdown-item"
                               href="{{ route('report', ['arn' => 'arn:aws:logs:eu-west-3:082157043056:log-group:total-preview', 'identifier' => 'total-pilot-preview']) }}">Pilot</a>
                        </li>
                        <li><a class="dropdown-item"
                               href="{{ route('report',  ['arn' => 'arn:aws:logs:eu-west-3:082157043056:log-group:total-preview', 'identifier' => 'total-optifi-preview']) }}">Optifi</a>
                        </li>
                        <li><a class="dropdown-item"
                               href="{{ route('report',  ['arn' => 'arn:aws:logs:eu-west-3:082157043056:log-group:total-preview', 'identifier' => 'total-comete-preview']) }}">Comete</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
</body>
</html>

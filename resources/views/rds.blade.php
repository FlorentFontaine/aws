@include('nav')
<link rel="stylesheet" href="{{ asset('css/rds.css') }}">
<h1 class="mb-5 text-center bg-dark text-white bg-secondary pb-4 shadow-lg">Cluster et instance RDS</h1>
<div class="m-4">
    <table class="table table-hover mt-4">
        <thead class="table-light">
        <tr>
            <th class="bg-secondary text-light">DB Cluster</th>
            <th class="sortable">cicd-app</th>
            <th class="sortable">cicd-client</th>
            <th class="sortable">cicd-version</th>
            <th>cicd-runmode</th>
            <th>cicd-commentaire</th>
            <th>DB Cluster Parameter Group</th>
            <th class="bg-secondary text-light">DB Instance</th>
            <th>cicd-app (Instance)</th>
            <th>cicd-client (Instance)</th>
            <th>cicd-version (Instance)</th>
            <th>DB Cluster Parameter Group (Instance)</th>
            <th>DB Instance Class</th>
        </tr>
        </thead>

        {{---------------------- Clusters RDS -----------------------}}

        @foreach ($dataClusters as $DBClusterIdentifier => $data)
            @php
                $bgColor = $loop->iteration % 2 ? '#FFF' : '#EEE';
                $idCluster = str_replace(":", "", $data['arn']);
                $appId = 'app_' . $idCluster;
                $clientId = 'client_' . $idCluster;
                $versionId = 'version_' . $idCluster;
                $runmodeId = 'runmode_' . $idCluster;
                $commentaireId = 'commentaire_' . $idCluster;
            @endphp
            <tr style="background-color: {{ (!$DBClusterIdentifier || $data['CopyTagsToSnapshot']) ? $bgColor : '#ff880060' }}">
                <form method="POST" action="{{ route('update-tag', ['identifier' => $data['arn']]) }}">
                    <td class="bg-secondary text-light" rowspan="{{ count($data['Instance']) }}" title="{{ print_r($data['Tags'], true) }}">{{ $DBClusterIdentifier }}</td>

                    {{-- Application --}}
                    @include('partials.row-column', ['itemId' => $appId, 'itemName' => 'cicd-app', 'itemValue' => $data['cicd-app']])

                    {{-- Client --}}
                    @include('partials.row-column', ['itemId' => $clientId, 'itemName' => 'cicd-client', 'itemValue' => $data['cicd-client']])

                    {{-- Version --}}
                    @include('partials.row-column', ['itemId' => $versionId, 'itemName' => 'cicd-version', 'itemValue' => $data['cicd-version']])

                    {{-- Run Mode --}}
                    @include('partials.row-column', ['itemId' => $runmodeId, 'itemName' => 'cicd-runmode', 'itemValue' => $data['cicd-runmode']])

                    {{-- Commentaire --}}
                    @include('partials.row-column', ['itemId' => $commentaireId, 'itemName' => 'cicd-commentaire', 'itemValue' => $data['cicd-commentaire']])

                    <td rowspan="{{ count($data['Instance']) }}">{{ $data['DBClusterParameterGroup'] }}</td>
                </form>
                @php $firstInstance = true; @endphp
                @php $firstInstance = true; @endphp
                @foreach ($data['Instance'] as $DBInstanceIdentifier => $data2)
                    @if (!$firstInstance)
            </tr>

            {{--------------------------------------Instances RDS--------------------------------}}

            <tr style="background-color: {{ (!$DBClusterIdentifier || $data['CopyTagsToSnapshot']) ? $bgColor : '#ff880060' }}">
                @endif
                @php
                    $commentaireInstanceId = 'commentaire_' . $DBInstanceIdentifier;
                    $versionInstanceId = 'version_' . $DBInstanceIdentifier;
                    $clientInstanceId = 'client_' . $DBInstanceIdentifier;
                    $appInstanceId = 'app_' . $DBInstanceIdentifier;
                    $runmodeInstanceId = 'runmode_' . $DBInstanceIdentifier;
                @endphp
                <td class="bg-secondary text-light" title="{{ print_r($data2['Tags'], true) }}">{{ $DBInstanceIdentifier }}</td>
                <form method="POST" action="{{ route('update-tag', ['identifier' => $data2['arn']]) }}">
                    {{--Application Instance--}}
                    @include('partials.row-column', ['itemId' => $appInstanceId, 'itemName' => 'cicd-app', 'itemValue' => $data2['cicd-app'],  'norowspan' => $data2 ])
                    {{-- Client Instance --}}
                    @include('partials.row-column', ['itemId' => $clientInstanceId, 'itemName' => 'cicd-client', 'itemValue' => $data2['cicd-client'],  'norowspan' => $data2 ])

                    {{-- Version Instance --}}
                    @include('partials.row-column', ['itemId' => $versionInstanceId, 'itemName' => 'cicd-version', 'itemValue' => $data2['cicd-version'],  'norowspan' => $data2 ])
                </form>
                <td>{{ $data2['DBClusterParameterGroup'] }}</td>
                <td>{{ $data2['DBInstanceClass'] }}</td>
                @php $firstInstance = false; @endphp
                @endforeach
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script>
    $(document).ready(function() {
        $(".sortable").click(function() {
            var columnIndex = $(this).index();
            var table = $(this).closest("table");
            var tbody = table.find("tbody");
            var rows = tbody.find("tr").toArray().sort(function(a, b) {
                var cellA = $(a).find("td").eq(columnIndex).text().toUpperCase();
                var cellB = $(b).find("td").eq(columnIndex).text().toUpperCase();
                if (cellA < cellB) {
                    return -1;
                }
                if (cellA > cellB) {
                    return 1;
                }
                return 0;
            });
            tbody.append(rows);
        });
    });
</script>

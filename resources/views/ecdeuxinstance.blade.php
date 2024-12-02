@include('nav')
<link rel="stylesheet" href="{{ asset('css/instanceEcDeux.css') }}">
<h1 class="mb-5 bg-dark text-center text-white bg-secondary pb-4 shadow-lg">Instances EC2</h1>
<div class="m-4">
    <table class="table mt-4" id="volumesTable">
        <thead class="table-light">
        <tr>
            <th style="min-width: 225px" class="sortable">Instance ID</th>
            <th class="sortable">Instance <br> Name</th>
            <th class="sortable">Instance Type</th>
            <th class="sortable">State</th>
            <th class="sortable">cicd-app</th>
            <th class="sortable">cicd-client</th>
            <th class="sortable">cicd-version</th>
            <th class="sortable">cicd-runmode</th>
            <th class="sortable">cicd-commentaire</th>
            <th>Tags</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sortedInstances as $instance)
            @php
                $bgColor = $loop->iteration % 2 ? '#FFF' : '#EEE';
                $appId = 'app_' . $instance['InstanceId'];
                $clientId = 'client_' . $instance['InstanceId'];
                $versionId = 'version_' . $instance['InstanceId'];
                $runmodeId = 'runmode_' . $instance['InstanceId'];
                $commentaireId = 'commentaire_' . $instance['InstanceId'];
            @endphp
            <tr style="background-color: {{ $bgColor }}">
                <form method="POST" action="{{ route("update-tagEcDeux", ["identifier" => $instance['InstanceId']]) }}">
                    <td style="padding-top: 30px">{{ $instance['InstanceId'] }}</td>
                    <td style="padding-top: 30px">{{ $instance['Name'] }}</td>
                    <td style="padding-top: 30px">{{ $instance['InstanceType'] }}</td>
                    <td style='color: {{ $instance['State'] == "stopped" ? "darkorange" : "inherit" }}; padding-top: 30px'>{{ $instance['State'] }}</td>

                    {{-- Application --}}
                    @include('partials.row-column', ['itemId' => $appId, 'itemName' => 'cicd-app', 'itemValue' => $instance['cicd-app'], 'norowspan' => $instance])

                    {{-- Client --}}
                    @include('partials.row-column', ['itemId' => $clientId, 'itemName' => 'cicd-client', 'itemValue' => $instance['cicd-client'], 'norowspan' => $instance])

                    {{-- Version --}}
                    @include('partials.row-column', ['itemId' => $versionId, 'itemName' => 'cicd-version', 'itemValue' => $instance['cicd-version'], 'norowspan' => $instance])

                    {{-- Run Mode --}}
                    @include('partials.row-column', ['itemId' => $runmodeId, 'itemName' => 'cicd-runmode', 'itemValue' => $instance['cicd-runmode'], 'norowspan' => $instance])

                    {{-- Commentaire --}}
                    @include('partials.row-column', ['itemId' => $commentaireId, 'itemName' => 'cicd-commentaire', 'itemValue' => $instance['cicd-commentaire'], 'norowspan' => $instance])
                    <td>
                        <button class="btn border border-1 mt-4" type="button" data-toggle="collapse"
                                data-target="#tagsCollapse{{ $loop->index }}" aria-expanded="false"
                                aria-controls="tagsCollapse{{ $loop->index }}" style="min-width: 400px">
                            Afficher les tags
                        </button>
                        <div style="max-width: 400px" class="collapse mt-3" id="tagsCollapse{{ $loop->index }}">
                            @php
                                $cicdClientPresent = false;
                                $cicdVersionPresent = false;
                                $cicdAppPresent = false;
                                $cicdCommentairePresent = false;
                                $cicdRunModePresent = false;
                            @endphp

                            @foreach ($instance['Tags'] as $tag)
                                @php
                                    if ($tag['Key'] === 'cicd-client') {
                                        $cicdClientPresent = true;
                                    } elseif ($tag['Key'] === 'cicd-app') {
                                        $cicdVersionPresent = true;
                                    } elseif ($tag['Key'] === 'cicd-version') {
                                        $cicdAppPresent = true;
                                    } elseif ($tag['Key'] === 'cicd-commentaire') {
                                        $cicdCommentairePresent = true;
                                    } elseif ($tag['Key'] === 'cicd-runmode') {
                                        $cicdRunModePresent = true;
                                    }
                                @endphp

                                @if (empty($tag['Value']))
                                    {{ $tag['Key'] }}: <span class="text-danger">Vide</span><br>
                                @elseif ($tag['Value'] == "inconnu")
                                    {{ $tag['Key'] }}: <span class="text-warning">{{ $tag['Value'] }}</span><br>
                                @else
                                    {{ $tag['Key'] }}: <span class="text-success">{{ $tag['Value'] }}</span><br>
                                @endif
                            @endforeach

                            {{-- Vérification des tags manquants et mise en forme --}}
                            @if (!$cicdClientPresent)
                                cicd-client:  <span class="text-danger">Manquant</span><br>
                            @endif
                            @if (!$cicdVersionPresent)
                                cicd-version: <span class="text-danger">Manquant</span><br>
                            @endif
                            @if (!$cicdAppPresent)
                                cicd-app: <span class="text-danger">Manquant</span><br>
                            @endif
                            @if (!$cicdCommentairePresent)
                                cicd-commentaire: <span class="text-danger">Manquant</span><br>
                            @endif
                            @if (!$cicdRunModePresent)
                                cicd-runmode: <span class="text-danger">Manquant</span><br>
                            @endif
                        </div>
                    </td>
                </form>
            </tr>

        @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script>
    $(document).ready(function() {
        $("#volumesTable").tablesorter({
            headers: {
                7: { sorter: false } // Disable sorting on the "Tags" column if necessary
            }
        });

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

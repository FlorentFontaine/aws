@include('nav')
<link rel="stylesheet" href="{{ asset('css/volumeEcDeux.css') }}">
<h1 class="mb-5 bg-dark text-center text-white bg-secondary pb-4 shadow-lg">Volumes EC2</h1>
<div class="m-5">
    <table class="table mt-4" id="volumesTable">
        <thead class="table-light">
        <tr>
            <th class="sortable">Instance ID</th>
            <th class="sortable">Volume ID</th>
            <th class="sortable">Volume Size</th>
            <th class="sortable">Cicd-app</th>
            <th class="sortable">Cicd-client</th>
            <th class="sortable">Cicd-version</th>
            <th class="sortable">Commentaire</th>
            <th class="sortable">Tags</th>
        </tr>
        </thead>
        <tbody>
        @foreach($volumes as $key => $volume)
            @php
                $bgColor = $loop->iteration % 2 ? '#FFF' : '#EEE';
                $requiredKeys = ['cicd-app', 'cicd-client', 'cicd-version', 'cicd-commentaire'];
                $existingKeys = array_column($volume['Tags'], 'Key');
                $missingKeys = array_diff($requiredKeys, $existingKeys);
                $appId = 'app_' . $volume['VolumeId'];
                $clientId = 'client_' . $volume['VolumeId'];
                $versionId = 'version_' . $volume['VolumeId'];
                $runmodeId = 'runmode_' . $volume['VolumeId'];
                $commentaireId = 'commentaire_' . $volume['VolumeId'];
            @endphp
            @if(!empty($volume['VolumeId']))
            <tr style="background-color: {{ $bgColor }}">
                <td style="padding-top: 32px">
                    {{ $volume['InstanceId'] }}
                    <br>
                    @foreach($instances as $instance)
                        @if(isset($instance['Tags']) && ($instance['InstanceId'] == $volume['InstanceId']))
                            @foreach($instance['Tags'] as $tag)
                                @if($tag['Key'] == 'Name')
                                    <strong>{{ $tag['Value'] ?? '' }}</strong>
                                @endif
                            @endforeach
                            </td>
                            <td style="padding-top: 32px">{{ $volume['VolumeId'] }}</td>
                            <td style="padding-top: 32px">{{ $volume['Size'] }} GB</td>
                            <form method="POST" action="{{ route('update-tagVolumeEcDeux', ['identifier' => $volume['VolumeId']]) }}">
                                @csrf

                                @foreach($instance['Tags'] as $tag)
                                    @if($tag['Key'] == 'cicd-app')
                                        @include('partials.row-column', ['itemId' => $appId, 'itemName' => 'cicd-app', 'itemValue' => $volume['cicd-app'], 'norowspan' => $volume ])
                                    @endif
                                @endforeach

                                @foreach($instance['Tags'] as $tag)
                                    @if($tag['Key'] == 'cicd-client')
                                        @include('partials.row-column', ['itemId' => $clientId, 'itemName' => 'cicd-client', 'itemValue' => $volume['cicd-client'], 'norowspan' => $volume ])
                                    @endif
                                @endforeach


                                @foreach($instance['Tags'] as $tag)
                                    @if($tag['Key'] == 'cicd-version')
                                        @include('partials.row-column', ['itemId' => $versionId, 'itemName' => 'cicd-version', 'itemValue' => $volume['cicd-version'], 'norowspan' => $volume ])
                                    @endif
                                @endforeach


                                @foreach($instance['Tags'] as $tag)
                                    @if($tag['Key'] == 'cicd-commentaire')
                                        @include('partials.row-column', ['itemId' => $commentaireId, 'itemName' => 'cicd-commentaire', 'itemValue' => $volume['cicd-commentaire'], 'norowspan' => $volume ])
                                    @endif
                                @endforeach
                            </form>
                        @endif
                    @endforeach
                    <td>
                        <button class="btn border border-1 mt-4" type="button" data-toggle="collapse"
                                data-target="#tagsCollapse{{ $loop->index }}" aria-expanded="false"
                                aria-controls="tagsCollapse{{ $loop->index }}" style="min-width: 400px">
                            Afficher les tags
                        </button>
                        <div style="max-width: 400px" class="collapse mt-3" id="tagsCollapse{{ $loop->index }}">
                            @if(isset($volume['Tags']))
                                @foreach($volume['Tags'] as $tag)
                                    @if ($tag['Value'] == '')
                                        {{ $tag['Key'] }}: <span class="text-danger">Vide</span><br>
                                    @elseif ($tag['Value'] == 'inconnu')
                                        {{ $tag['Key'] }}: <span class="text-warning"> {{ $tag['Value'] }}</span><br>
                                    @else
                                        {{ $tag['Key'] }}: <span class="text-success"> {{ $tag['Value'] }}</span><br>
                                    @endif
                                @endforeach
                                @foreach($missingKeys as $missingKey)
                                    {{ $missingKey }}: <span class="text-danger">Manquant</span><br>
                                @endforeach
                            @else
                                <div>Aucun tag disponible</div>
                        @endif
                    </td>
                </tr>
            @endif
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

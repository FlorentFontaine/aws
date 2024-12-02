@if(!isset($norowspan))
    <td rowspan="{{ count($data['Instance']) }}">
@elseif(isset($DBClusterIdentifier))
    <td rowspan="1" style="background-color: {{ (!$DBClusterIdentifier || $data[$itemName] === $data2[$itemName]) ? '' : '#ff880060' }}; min-width: 170px">
@elseif(isset($instance) && isset($volume) && ($tag['Key'] == $itemName))
    <td style="background-color: {{ $volume[$itemName] === $tag['Value'] ? '' : '#ff880060' }}; min-width: 170px">
@elseif(isset($instance))
    <td style="min-width: 170px">
@elseif(isset($tag) && !empty($itemId))
    <td style="min-width: 170px">
        @endif
        <div class="d-flex justify-content-between pt-4">
            @if($itemName == "cicd-runmode" || $itemName == "cicd-commentaire")
                <span style="color: red; margin-right: 10px" id="td_{{ $itemId }}">{{ $itemValue }}</span>
            @else
                <span class="mr-4" id="td_{{ $itemId }}">{{ $itemValue }}</span>
            @endif
            @if (!empty($itemValue) && !empty($itemId))
                <button type="button" id="btn_{{ $itemId }}" onclick="modalEdit('{{ $itemId }}', '{{ $itemValue }}')" class="btn border border-1 text-left custom-btn"><i class="fas fa-edit"></i></button>
            @else
                <button type="button" id="btn_{{ $itemId }}" onclick="modalEdit('{{ $itemId }}', '')" class="btn border border-1 custom-btn"><i class="fas fa-plus"></i></button>
            @endif
        </div>

        <!-- Modal Bootstrap -->
        <div class="modal fade" id="{{ $itemId }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-light">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{$itemName}}</h5>
                    </div>
                    <div class="modal-body">
                        @include('partials.edit-form', ['itemId' => $itemId, 'itemName' => $itemName, 'itemValue' => $itemValue])
                    </div>
                    <button type="button" onclick="closeModal('{{ $itemId }}')" class="btn btn-secondary reset-button">Fermer</button>
                </div>
            </div>
        </div>
    </td>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .reset-button {
            border-radius: 0; /* Réinitialiser le border radius */
        }
    </style>
    <script>
        function closeModal(itemId) {
            $('#' + itemId).modal('hide');
        }

        // Fonction pour ouvrir la modal
        function modalEdit(itemId) {
            $('#' + itemId).modal('show');
        }

        $(document).ready(function() {
            // Sélectionner le bouton de déclenchement de la modal
            let openModalButton = $('#btn_{{ $itemId }}');

            // Vérifier si le bouton est trouvé
            if (openModalButton.length > 0) {
                openModalButton.on('click', function() {
                    modalEdit('{{ $itemId }}');
                });
            } else {
                console.error("Le bouton de déclenchement de la modal n'est pas trouvé.");
            }
        });
    </script>

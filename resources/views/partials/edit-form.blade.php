<div id="{{ $itemId }}" class="card mt-4 mb-4 border border-0">
    <div class="card-body">
        @csrf
        @method('PUT')
        <div class="form-group mb-2">
            <label for="{{ $itemName }}">{{ ucfirst($itemName) }} :</label>
            @if($itemName == 'cicd-version')
                <select id="{{ $itemName }}" name="{{ $itemName }}" class="form-control" style="width: 300px;">
                    <option value="" {{ $itemValue == '' ? 'selected' : '' }}></option>
                    <option value="prod" {{ $itemValue == 'prod' ? 'selected' : '' }}>prod</option>
                    <option value="test" {{ $itemValue == 'test' ? 'selected' : '' }}>test</option>
                    <option value="demo" {{ $itemValue == 'demo' ? 'selected' : '' }}>demo</option>
                    <option value="bac-a-sable" {{ $itemValue == 'bac-a-sable' ? 'selected' : '' }}>bac-a-sable</option>
                    <option value="rip" {{ $itemValue == 'rip' ? 'selected' : '' }}>rip</option>
                    <option value="preprod" {{ $itemValue == 'preprod' ? 'selected' : '' }}>preprod</option>
                    <option value="inconnu" {{ $itemValue == 'inconnu' ? 'selected' : '' }}>inconnu</option>
                </select>
            @elseif($itemName == 'cicd-runmode')
                <select id="cicd-runmode" name="cicd-runmode" class="form-control" style="width: 300px;">
                    <option value="" {{ $itemValue== '' ? 'selected' : '' }}></option>
                    <option value="start" {{ $itemValue == 'start' ? 'selected' : '' }}>start</option>
                    <option value="stop" {{ $itemValue == 'stop' ? 'selected' : '' }}>stop</option>
                    <option value="schedule" {{ $itemValue == 'schedule' ? 'selected' : '' }}>schedule</option>
                    <option value="schedule" {{ $itemValue == 'schedule' ? 'selected' : '' }}>restart</option>
                </select>
            @elseif($itemName == 'cicd-client')
                <select id="cicd-client" name="cicd-client" class="form-control" style="width: 300px;">
                    <option value="" {{ $itemValue == '' ? 'selected' : '' }}></option>
                    <option value="afm" {{ $itemValue == 'afm' ? 'selected' : '' }}>afm</option>
                    <option value="aspbtp" {{ $itemValue == 'aspbtp' ? 'selected' : '' }}>aspbtp</option>
                    <option value="aviap" {{ $itemValue == 'aviap' ? 'selected' : '' }}>aviap</option>
                    <option value="aviatd" {{ $itemValue == 'aviatd' ? 'selected' : '' }}>aviatd</option>
                    <option value="bp" {{ $itemValue == 'bp' ? 'selected' : '' }}>bp</option>
                    <option value="cicd" {{ $itemValue == 'cicd' ? 'selected' : '' }}>cicd</option>
                    <option value="complevie" {{ $itemValue == 'complevie' ? 'selected' : '' }}>complevie</option>
                    <option value="commun" {{ $itemValue == 'commun' ? 'selected' : '' }}>commun</option>
                    <option value="divers" {{ $itemValue == 'divers' ? 'selected' : '' }}>divers</option>
                    <option value="eni" {{ $itemValue == 'eni' ? 'selected' : '' }}>eni</option>
                    <option value="mygest" {{ $itemValue == 'mygest' ? 'selected' : '' }}>mygest</option>
                    <option value="sigess" {{ $itemValue == 'sigess' ? 'selected' : '' }}>sigess</option>
                    <option value="sighor" {{ $itemValue == 'sighor' ? 'selected' : '' }}>sighor</option>
                    <option value="totalenergies-nc" {{ $itemValue == 'totalenergies-nc' ? 'selected' : '' }}>totalenergies-nc</option>
                    <option value="totalenergies" {{ $itemValue == 'totalenergies' ? 'selected' : '' }}>totalenergies</option>
                    <option value="inconnu" {{ $itemValue == 'inconnu' ? 'selected' : '' }}>inconnu</option>
                </select>
            @else
                <input type="text" id="{{ $itemName }}" name="{{ $itemName }}" value="{{ $itemValue ?? '' }}" class="form-control" style="width: 300px;">
            @endif
        </div>
        <button type="submit" class="btn border border-1 btn-light hover-shadow"><i class="fas fa-save"></i> Enregistrer</button>
    </div>
</div>

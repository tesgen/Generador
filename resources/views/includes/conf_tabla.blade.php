<div class="form-group">
    <div class="form-group">
        <label>Selecionar:</label>
        <select id="combo-tabla-{{$tipoTabla}}" onchange="mostrarDatosTabla('{{$tipoTabla}}')"
                class="form-control selectpicker">
            @foreach($array_tablas as $tabla)
                <option>{{$tabla['nombreTabla']}}</option>
            @endforeach
        </select>
    </div>
</div>

<div id="conf-tabla-{{$tipoTabla}}">
    <div class="form-row">
        <div class="form-group col-sm-4">
            <label>Nombre Clase:</label>
            <input type="text" class="form-control nombreClase">
        </div>
        <div class="form-group col-sm-4">
            <label>Nombre Natural: </label>
            <input type="text" class="form-control nombreNatural">
        </div>
        <div class="form-group col-sm-4">
            <label>Nombre Plural: </label>
            <input type="text" class="form-control nombrePlural">
        </div>
    </div>
    <div @if($tipoTabla == 'detalle') hidden @endif class="form-row">
        <div class="form-group col-sm-4">
            <input id="checkGenerarApi" type="checkbox" class="checkGenerarApi">
            <label for="checkGenerarApi">Generar API</label>
        </div>
    </div>
</div>

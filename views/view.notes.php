<?php
require_once __DIR__ . "/view.header.php";
?>
<div class="text-center w-100 titulo1 mb-3">Notas</div>
<div id="average" class="text-center w-100 subtitulo1 mb-3">Promedio: 0</div>
<div id="divAcction" class="w-100 d-flex justify-content-end mb-3">
    <div class="my-auto">
        <div class="btnAdd" data-bs-toggle="tooltip" data-bs-placement="top" title="Agregar" onclick="showNoteModal();">
            <i class="fa fa-2x fa-plus" aria-hidden="true"></i>
        </div>
    </div>
</div>
<div id="loading" class="w-100 text-center">
    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
</div>
<div id="alertNotes"></div>
<table id="notestable" class="table table-striped table-hover d-none">
    <caption>Calificaciones</caption>
    <thead>  
        <th scope="col" class="text-center">Materia</th>
        <th scope="col" class="text-center">Nota</th>
        <th scope="col" class="text-center" width="10%">Acción</th>
    </thead>
    <tbody id="tbodynotes"></tbody>
</table>
<script>

function showNoteModal(id, matter, score){
    let action = id ? "Editar" : "Agregar";
    let html = `<form id="formNote">
        <input type="hidden" name="noteid" id="noteid" value="${ id ?? ''}">
        <div class="mb-3">
            <input type="text" class="form-control" value="${ matter ?? ''}" name="matter" id="matter" placeholder="Materia" onkeypress="return validar(event, letras+numeros);" onkeyup="noteValidate();" onblur="capitalFormat(this);" minlength="5" maxlength="50" required>
            <small class="invalid-feedback">Ingrese una materia válida</small>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" value="${ score ?? ''}" name="score" id="score" placeholder="Nota" onkeypress="return validar(event, numeros);" onkeyup="noteValidate();" minlength="1" maxlength="2" required>
            <small class="invalid-feedback">Ingrese una nota válida</small>
        </div>
    </form>`;
    $("#misscarModal .modal-body").html(html);
    $("#misscarModal .modal-footer button[data-bs-dismiss=\"modal\"]").html("Cancelar");
    $("#misscarModal .modal-footer button:last-child").unbind("click");
    $("#misscarModal .modal-footer button:last-child").click(()=>{ sendNote(); });
    $("#misscarModal .modal-footer button:last-child").html(action);
    $("#misscarModal .modal-footer button:last-child").prop("disabled", true);
    $("#btnShowModal").click();
}

function showNoteDelModal(id){
    let html = `¿Desea eliminar la nota?`;
    $("#misscarModal .modal-body").html(html);
    $("#misscarModal .modal-footer button[data-bs-dismiss=\"modal\"]").html("Cancelar");
    $("#misscarModal .modal-footer button:last-child").unbind("click");
    $("#misscarModal .modal-footer button:last-child").click(()=>{ delNote(id); });
    $("#misscarModal .modal-footer button:last-child").html('Eliminar');
    $("#misscarModal .modal-footer button:last-child").prop("disabled", false);
    $("#btnShowModal").click();
}

function noteValidate(){
    $("#misscarModal .modal-footer button:last-child").prop("disabled", true);
    if($('#matter').val().length < 5 || isNaN(parseInt($('#score').val()))) return;
    if(parseInt($('#score').val()) > 10) return $('#score').val('').select();
    $("#misscarModal .modal-footer button:last-child").prop("disabled", false);
}

function sendNote(){
    $.post({
        url: 'apis/api.notes.php',
        dataType: 'json',
        data:{
            action: 'addnote',
            id: $('#noteid').val(),
            matter: $('#matter').val().trim(),
            score: parseInt($('#score').val())
        },
        beforeSend: function(){
            window.modalaction = $("#misscarModal .modal-footer button:last-child").html();
            $("#misscarModal .modal-footer button:last-child").html('<i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
        },
        success: function(){
            $("#misscarModal .modal-footer button[data-bs-dismiss=\"modal\"]").click();
            refreshList();
        },
        error: function(xhr, textStatus, textError){
            if(xhr.status == 0){
                $("#modalError").css("color", "red").html("Sin conexión a Internet");
            }else if(xhr.status == 400){
                let data = JSON.parse(xhr.responseText);
                if(data.state == "user without an active session") window.location.reload(true);
            }else{
                $("#modalError").css("color", "red").html("Ha ocurrido un error");
            }
        },
        complete: function(){
            $("#misscarModal .modal-footer button:last-child").html(window.modalaction);
            delete window.modalaction;
        }
    });
}

function delNote(id){
    $.post({
        url: 'apis/api.notes.php',
        dataType: 'json',
        data:{
            action: 'delnote',
            id
        },
        beforeSend: function(){
            window.modalaction = $("#misscarModal .modal-footer button:last-child").html();
            $("#misscarModal .modal-footer button:last-child").html('<i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
        },
        success: function(){
            $("#misscarModal .modal-footer button[data-bs-dismiss=\"modal\"]").click();
            refreshList();
        },
        error: function(xhr, textStatus, textError){
            let data = JSON.parse(xhr.responseText);
            if(xhr.status == 0){
                $("#modalError").css("color", "red").html("Sin conexión a Internet");
            }else if(xhr.status == 400){
                let data = JSON.parse(xhr.responseText);
                if(data.state == "user without an active session") window.location.reload(true);
            }else{
                $("#modalError").css("color", "red").html("Ha ocurrido un error");
            }
        },
        complete: function(){
            $("#misscarModal .modal-footer button:last-child").html(window.modalaction);
            delete window.modalaction;
        }
    });
}

function refreshList(){
    $.get({
        url: "apis/api.notes.php",
        dataType: "json",
        data:{
            action: "getnotes"
        },
        beforeSend: function(){
            $("#loading").removeClass("d-none");
            $("#notestable").addClass("d-none");
            $("#notestable tbody").empty();
        },
        success: function(data){
            let average = 0;
            data.forEach(note => {
                let html = `<tr>
                    <td class="text-center">${note.matter}</td>
                    <td class="text-center">${note.score}</td>
                    <td class="text-center">
                        <i class="fa fa-pencil pointer" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" onclick="showNoteModal(${note.id}, '${note.matter}', ${note.score});"></i>
                        <i class="fa fa-trash pointer" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar" onclick="showNoteDelModal(${note.id});"></i>
                    </td>
                </tr>`;
                $("#notestable tbody").append(html);
                average += parseInt(note.score);
            });
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('#notestable tbody tr td i[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            if(average > 0) average = average / data.length;
            $('#notestable caption').html(`Calificaciones (Promedio: ${ average })`);
            $('#average').html(`Promedio: ${ average }`);
        },
        error: function(xhr, textStatus, textError){
            if(xhr.status == 0){
                $("#alertUsers").html(`<div class="alert alert-warning alert-dismissible fade show mt-5" role="alert">
                <strong>¡Sin conexión de internete!</strong> Verifique su conexión.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`);
            }else if(xhr.status == 400){
                let data = JSON.parse(xhr.responseText);
                if(data.state == "user without an active session") window.location.reload(true);
            }else{
                $("#alertUsers").html(`<div class="alert alert-warning alert-dismissible fade show mt-5" role="alert">
                <strong>¡Ups, ha ocurrido un error!</strong> Intente nuevamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`);
            }
        },
        complete: function(){
            let html = `<tr>
                <td colspan="7" class="text-center">No existen datos a mostrar</td>
            </tr>`;
            $("#loading").addClass("d-none");
            $("#notestable").removeClass("d-none");
            if($("#notestable tbody tr").length == 0) $("#notestable tbody").append(html);
        }
    });
}

refreshList();

</script>
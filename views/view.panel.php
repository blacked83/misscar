<?php
require_once __DIR__ . "/view.header.php";

$pollIcon = file_get_contents(__DIR__ . "/../assets/img/icons/poll.svg");
$logoutIcon = file_get_contents(__DIR__ . "/../assets/img/icons/logout.svg");
?>
<div class="container" id="container">
    <div class="menu">
        <div class="menu_items">
            <div class="menuItem my-lg-3" id="btnNotes" data-bs-toggle="tooltip" data-bs-placement="top" title="Notas"><?=$pollIcon?></div>    
            <div class="menuItem my-lg-3" id="btnLogout" data-bs-toggle="tooltip" data-bs-placement="top" title="Salir"><?=$logoutIcon?></div>
        </div>
    </div>
    <div id="mainsecction" class="container-fluid">
        <div class="main"></div>
        <div id="footer">
            <small>Power By ©MissCar <?=date("Y");?></small>
        </div>
    </div>
</div>
<!-- Button trigger modal -->
<button type="button" id="btnShowModal" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#misscarModal">
  Launch demo modal
</button>
<!-- Modal -->
<div class="modal fade" id="misscarModal" tabindex="-1"  aria-labelledby="hughesnetModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hughesnetModalTitle">MissCar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <small id="modalError"></small>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
function loadSections(obj){
    let viewPage = "", icon = "";
    if(obj.id == "btnLogout"){
        $.post({
            url: "apis/api.login.php",
            dataType: "html",
            data:{
                action: "logout"
            },
            beforeSend: function(){
                icon = $(`#${obj.id}`).html();
                $(`#${obj.id}`).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
            },  
            success: function(html){
                window.location.reload(true);
            },
            error: function(xhr, dataError, textError){
                if(xhr.status == 0){
                    alert("¡Sin internet!, revisa tu conexión a internet, he intenta nuevamente.");
                }else if(xhr.status == 400){
                    let data = JSON.parse(xhr.responseText);
                    if(data.state == "user without an active session")
                        window.location.reload(true);
                }else{
                    alert("¡Ups!, ha ocurrido un error, intenta nuevamente.");
                }
            },
            complete: function(){
                $(`#${obj.id}`).html(icon);
            }
        });
    }else{
        switch(obj.id){
            case "btnNotes":
                viewPage = "views/view.notes.php";
                break;
        }
        $.get({
            url: viewPage,
            dataType: "html",
            beforeSend: function(){
                icon = $(`#${obj.id}`).html();
                $(`#${obj.id}`).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
            }, 
            success: function(html){
                $(".main").html(html);
                refreshTooltip();
            },
            error: function(xhr, textStatus, textError){
                if(xhr.status == 0){
                    $("#misscarModal .modal-body").html("¡Sin internet!, revisa tu conexión a internet, he intenta nuevamente.");
                    $("#misscarModal .modal-footer .btn-danger").addClass("d-none");
                    $("#misscarModal .modal-footer .btn-secondary").html("Cerrar").click(() => {
                        setTimeout(() => {
                            $("#misscarModal .modal-footer .btn-secondary").html("Cancelar").unbind("click");
                            $("#misscarModal .modal-footer .btn-danger").removeClass("d-none");
                        }, 1000);
                    });
                    $("#btnShowModal").click();
                }else if(xhr.status == 400){
                    let data = JSON.parse(xhr.responseText);
                    if(data.state == "user without an active session")
                        window.location.reload(true);
                }else{
                    $("#misscarModal .modal-body").html("¡Ups!, ha ocurrido un error, intenta nuevamente.");
                    $("#misscarModal .modal-footer .btn-danger").addClass("d-none");
                    $("#misscarModal .modal-footer .btn-secondary").html("Cerrar").click(() => {
                        setTimeout(() => {
                            $("#misscarModal .modal-footer .btn-secondary").html("Cancelar").unbind("click");
                            $("#misscarModal .modal-footer .btn-danger").removeClass("d-none");
                        }, 1000);
                    });
                    $("#btnShowModal").click();
                }
            },
            complete: function(){
                $(`#${obj.id}`).html(icon);
            }
        });
    }
}

$(".menu .menu_items div[id^='btn']").click(function(){
    loadSections(this);
});

$(".menuItem").click(function(){
    $(".menuItem").children("svg").removeClass("active");
    $(this).children("svg").addClass("active");
});


var tooltipTriggerList = [].slice.call(document.querySelectorAll('.menu [data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

function refreshTooltip(){
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('.main [data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
}

if(window.innerHeight < window.innerWidth)
    $("#mainsecction .main").css("height", (window.innerHeight - 130) + "px");

</script>
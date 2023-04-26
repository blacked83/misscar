<div class="login">
    <div id="backlink">
        <img src="assets/img/backlink.svg" alt="">
    </div>
    <img src="assets/img/logo.png" class="logo" alt="HughesNet">
    <div id="form">
        <form id="formLogin" autocomplete="off">
            <div>
                <input type="email" name="email" id="email" class="form-control" onkeypress="return validar(event, numeros+letras+'_-@.');" placeholder="Correo" required>
                <small class="invalid-feedback">Ingrese un correo válido</small>
            </div>
            <div class="pt-5">
                <div class="input-group" id="passgroup">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña (mínimo 8 caracteres)" minlength="8" required>
                    <span class="input-group-text pointer">
                        <img class="viewpass" src="assets/img/passfree.png" alt="">
                    </span>
                </div>
                <small class="invalid-feedback">Debe ingresar mínimo 8 caracteres</small>
            </div>
            <div class="pt-5">
                <button type="button" id="btnLogin" class="btn-primario" disabled>Iniciar Sesión</button>
            </div>
            <div class="pt-2">
                <span id="register" class="forgetpass">Crear una cuenta</span>
            </div>
            <div class="pt-2" id="alertLogin"></div>
        </form>
        <form id="formRegister" style="display:none;" autocomplete="off">
            <div class="mb-3">
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Nombres" minlength="2" onblur="capitalFormat(this);" onkeypress="return validar(event, letras)" required>
                <small class="invalid-feedback">Ingrese un nombre válido</small>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Apellidos" minlength="2" onblur="capitalFormat(this);" onkeypress="return validar(event, letras)" required>
                <small class="invalid-feedback">Ingrese un apellido válido</small>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="emailreg" id="emailreg" placeholder="Correo electrónico" required>
                <small class="invalid-feedback">Ingrese un correo válido</small>
                <small id="emailregexists" class="text-danger invisible fs-6">El correo electrónico ya existe</small>
            </div>
            <div class="mb-3">
                <input type="tel" class="form-control" name="phone" id="phone" required placeholder="Teléfono" minlength="9" maxlength="9" onkeypress="return validar(event, numeros)">
                <small class="invalid-feedback">Ingrese un teléfono válido</small>
                <small id="phoneexists" class="text-danger invisible fs-6">El teléfono ya existe</small>
            </div>
            <div class="mb-3">
                <input type="date" class="form-control" name="birthday" id="birthday" onchange="registerValidated();" required>
                <small class="invalid-feedback">Ingrese un fecha de nacimiento válida</small>
                <small id="age" class="text-danger invisible fs-6">Debe ser mayor a 18 años</small>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="passwordreg" id="passwordreg" minlength="8" placeholder="Contraseña" required>
                <small class="invalid-feedback">Debe coincidir la contraseña</small>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="confirmpass" id="confirmpass" minlength="8" placeholder="Confirmar Contraseña" required>
                <small class="invalid-feedback">Debe ingresar mínimo 8 caracteres</small>
                <small id="confirmpassMsg" class="text-danger invisible fs-6">La confirmación debe coincidir</small>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="terms">
                <label class="form-check-label" for="terms">Acepto Términos y Condiciones</label required>
            </div>
            <div class="pt-5">
                <button type="button" id="btnRegister" class="btn-primario" disabled>Crear cuenta</button>
            </div>
            <div class="pt-2" id="alertRegister"></div>
        </form>
    </div>
</div>
<script>

function loginCheck(){
    if($("#email").val().length == 0 || $("#password").val().length == 0)
        return $("#btnLogin").prop("disabled", true);
    if((!is_email($("#email").val()) && $("#email").val().length > 0) || ($("#password").val().length > 0 && $("#password").val().length < 8)){
        $("#btnLogin").prop("disabled", true);
        return $("#formLogin").addClass("was-validated");
    }
    $("#btnLogin").prop("disabled", false); 
}

function registerValidated(){
    $('#confirmpassMsg').addClass('invisible');
    $('#age').addClass('invisible');
    let age = 0;
    if($('#birthday').val().length > 0) age = getAge($('#birthday').val());
    if(
        $('#firstname').val().length < 2 || 
        $('#lastname').val().length < 2 || 
        !is_email($("#emailreg").val()) || 
        !is_phone($("#phone").val())
    ){
        $("#formRegister").addClass("was-validated");
        $("#btnRegister").prop("disabled", true);
        return false;
    }
    if(age < 18){
        $('#age').removeClass('invisible');
        $("#btnRegister").prop("disabled", true);
        return false;
    }
    if($('#passwordreg').val().length < 8){
        $("#formRegister").addClass("was-validated");
        $("#btnRegister").prop("disabled", true);
        return false;
    }
    if( $('#passwordreg').val() != $('#confirmpass').val()){
        $('#confirmpassMsg').removeClass('invisible');
        $("#btnRegister").prop("disabled", true);
        return false;
    }
    if($('#terms').prop('checked') == false){
        $("#formRegister").addClass("was-validated");
        $("#btnRegister").prop("disabled", true);
        return false;
    }
    $("#btnRegister").prop("disabled", false);
}

$("#email, #password").keyup(function(event){
    let keycode = (event.keyCode ? event.keyCode : event.which);
    let state = $("#btnLogin").prop("disabled");
    if(this.id == "password"){
        if(keycode == 13 && state === false){
            $("#btnLogin").prop("disabled", true);
            $("#btnLogin").click();
        }else if(keycode != 13){
            loginCheck();
        }
    }else{
        loginCheck();
    }
});

$("#firstname, #lastname, #emailreg, #phone, #birthday, #passwordreg, #confirmpass").keyup(() => { registerValidated(); });

$('#terms').click(() => { registerValidated(); });

$("#register").click(()=>{
    window.activeForm = '#formRegister';
    $("#formLogin").removeClass("was-validated");
    $("#formLogin")[0].reset();
    $("#formRegister")[0].reset();
    $("#formLogin").slideToggle("slow");
    $("#formRegister").slideToggle("slow");
    if($("#backlink").css("visibility") == "visible"){
        $("#backlink").css("visibility", "hidden");
    }else{
        $("#backlink").css("visibility", "visible");
    }
});

$("#backlink").click(()=>{
    $("#formLogin").removeClass("was-validated");
    $("#formLogin")[0].reset();
    $("#formLogin").slideToggle("slow");
    $(window.activeForm)[0].reset();
    $(window.activeForm).removeClass("was-validated");
    $(window.activeForm).slideToggle("slow");
    if($("#backlink").css("visibility") == "visible"){
        $("#backlink").css("visibility", "hidden");
    }else{
        $("#backlink").css("visibility", "visible");
    }
})

$("#btnLogin").click(()=>{
    $.post({
        url: "apis/api.login.php",
        dataType: "json",
        data:{
            action: 'login',
            email: $("#email").val().trim(),
            password: sha512($("#password").val().trim())
        },
        beforeSend: function(){
            $("#btnLogin").prop("disabled", true);
            $("#email").parent().children(".invalid-feedback").html("Ingrese un correo válido");
            $("#email").parent().children(".invalid-feedback").hide();
            $("#password").parent().children(".invalid-feedback").html("Debe ingresar mínimo 8 caracteres");
            $("#password").parent().children(".invalid-feedback").hide();
            $("#btnLogin").html('<i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
        },
        success: function(data){
            changeViewPanel();
        },
        error: function(xhr, textStatus, typeError){
            if(xhr.status == 0){
                let alert = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ¡Sin internet!, revisa tu conexión a internet, he intenta nuevamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
                $("#alertLogin").html(alert);
            }else if(xhr.status == 400){
                let data = JSON.parse(xhr.responseText);
                if(data.state == "invalid email"){
                    $("#email").parent().children(".invalid-feedback").html("Correo electrónico no registrado");
                    $("#email").parent().children(".invalid-feedback").show();
                    $("#email").select();
                }else if(data.state == "invalid password"){
                    $("#password").parent().next(".invalid-feedback").html("Contraseña inválida");
                    $("#password").parent().next(".invalid-feedback").show();
                    $("#password").select();
                }
            }else{
                let alert = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ¡Ups!, ha ocurrido un error, intenta nuevamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
                $("#alertLogin").html(alert);
            }
        },
        complete: function(){
            $("#btnLogin").prop("disabled", false);
            $("#btnLogin").html('Iniciar Sesión');
        }
    });
});

$("#btnRegister").click(()=>{
    alert($("#birthday").val());
    $.post({
        url: "apis/api.login.php",
        dataType: "json",
        data:{
            action: 'register',
            email: $("#emailreg").val().trim(),
            password: sha512($("#passwordreg").val().trim()),
            phone: $("#phone").val().trim(),
            firstname: $("#firstname").val().trim(),
            lastname: $("#lastname").val().trim(),
            birthday: $("#birthday").val().trim()
        },
        beforeSend: function(){
            $("#btnRegister").html('<i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>').prop("disabled", true);
            $("#emailregexists").addClass('invisible');
            $("#phoneexists").addClass('invisible');
        },
        success: function(data){
            $("#backlink").click();
        },
        error: function(xhr, textStatus, typeError){
            if(xhr.status == 0){
                let alert = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ¡Sin internet!, revisa tu conexión a internet, he intenta nuevamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
                $("#alertRegister").html(alert);
            }else if(xhr.status == 400){
                let data = JSON.parse(xhr.responseText);
                if(data.state == "email already exists"){
                    $("#emailregexists").removeClass('invisible');
                    $("#emailreg").select();
                }
                if(data.state == "phone already exists"){
                    $("#phoneexists").removeClass('invisible');
                    $("#phone").select();
                }
            }else{
                let alert = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ¡Ups!, ha ocurrido un error, intenta nuevamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
                $("#alertRegister").html(alert);
            }
        },
        complete: function(){
            $("#btnRegister").prop("disabled", false).html('Crear cuenta');
        }
    });
});

$(".input-group .form-control").focus(function(){
    $(this).parent().children(".input-group-text").css("border-bottom-color","var(--orange)");
}).blur(function(){
    $(this).parent().children(".input-group-text").css("border-bottom-color","var(--green)");
});


$(".input-group .input-group-text").click(function(){
    let state = $(this).children(".viewpass").prop("src");
    if(state.indexOf("passfree.png") > -1){
        $(this).prev().prop("type","text");
        state = state.replace("passfree.png", "passmask.png");
    }else{
        $(this).prev().prop("type","password");
        state = state.replace("passmask.png", "passfree.png");
    }
    $(this).children(".viewpass").attr("src", state);
});

function getAge(birthdayString){
    let birthday = new Date(birthdayString); 
    let ageTime = Date.now() - birthday.getTime();
    let ageDate = new Date(ageTime);
    let age = Math.abs(ageDate.getUTCFullYear() - 1970);
    console.log('Age: ', age);
    return age;
}


function changeViewPanel(){
    $.get({
        url: 'views/view.panel.php',
        beforeSend: function(){
            $("#btnLogin").html('<i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i>');
        },
        success: function(html){
            $("body").html(html);
        },
        error: function(xhr, textStatus, textError){
            
        },
        complete: function(){
            $("#btnLogin").html('Iniciar sesión');
        }
    });
}

</script>
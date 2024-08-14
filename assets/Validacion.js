document.addEventListener("DOMContentLoaded", function() {
    var formulario = document.getElementById("login-form");
  
    formulario.addEventListener("submit", function(event) {
        var usuario = document.getElementById("usuario").value;
        var password = document.getElementById("password").value;
        var usuarioError = document.getElementById("usuario-error");
        var passwordError = document.getElementById("password-error");
        var isValid = true;
  
        usuarioError.textContent = "";
        passwordError.textContent = "";
  
        if (usuario.trim() === "") {
            usuarioError.textContent = "Por favor ingrese su documento.";
            isValid = false;
        }
  
        if (password.trim() === "") {
            passwordError.textContent = "Por favor ingrese su contraseña.";
            isValid = false;
        }
  
        if (!isValid) {
            event.preventDefault();
        }
    });
  });
  
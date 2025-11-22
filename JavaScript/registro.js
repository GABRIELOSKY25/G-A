document.addEventListener('DOMContentLoaded', function() {
    // 1. Obtener referencias a los elementos del formulario
    const form = document.querySelector('.formulario');
    const nombreCompleto = document.getElementById('nombre_completo');
    const telefono = document.getElementById('telefono');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmarPassword = document.getElementById('confirmar_password');
    const terminos = document.getElementById('terminos');
    const passwordHint = document.querySelector('.password_hint');

    // 2. Función para mostrar mensajes de error (simplemente usando la alerta por simplicidad)
    // En un entorno de producción, es mejor mostrar los errores junto a los campos.
    function mostrarError(campo, mensaje) {
        alert(`Error en ${campo}: ${mensaje}`);
    }

    // 3. Función para validar el formato de correo electrónico
    function validarEmail(email) {
        // Expresión regular simple para verificar el formato de email: 'algo@dominio.com'
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    // 4. Función para validar la complejidad de la contraseña
    function validarPassword(password) {
        // Expresión regular que requiere:
        // - Mínimo 8 caracteres de longitud: .{8,}
        // - Al menos una letra mayúscula: (?=.*[A-Z])
        // - Al menos un número: (?=.*\d)
        const re = /^(?=.*[A-Z])(?=.*\d).{8,}$/;
        return re.test(String(password));
    }
    
    // 5. Función de manejo de envío del formulario
    form.addEventListener('submit', function(event) {
        // Prevenir el envío por defecto para realizar la validación
        event.preventDefault(); 
        
        let esValido = true; // Variable de control de validación

        // --- Validación de Nombre Completo (Verificar que no esté vacío, aunque 'required' en HTML ya lo hace)
        if (nombreCompleto.value.trim() === '') {
            mostrarError('Nombre Completo', 'El nombre completo es obligatorio.');
            esValido = false;
        }

        // --- Validación de Teléfono (10 dígitos exactos)
        const telefonoValor = telefono.value.trim();
        if (telefonoValor.length !== 10 || isNaN(telefonoValor)) {
            mostrarError('Teléfono', 'Debe contener exactamente 10 dígitos numéricos.');
            esValido = false;
        }

        // --- Validación de Correo Electrónico (Formato correcto)
        if (!validarEmail(email.value.trim())) {
            mostrarError('Correo Electrónico', 'El formato del correo electrónico no es válido (ej: tu@email.com).');
            esValido = false;
        }

        // --- Validación de Contraseña (Complejidad)
        if (!validarPassword(password.value)) {
            mostrarError('Contraseña', 'La contraseña debe tener al menos 8 caracteres, una mayúscula y un número.');
            // También se podría resaltar el hint: passwordHint.style.color = 'red';
            esValido = false;
        }

        // --- Validación de Confirmación de Contraseña
        if (password.value !== confirmarPassword.value) {
            mostrarError('Confirmar Contraseña', 'Las contraseñas no coinciden.');
            esValido = false;
        }
        
        // --- Validación de Aceptación de Términos
        if (!terminos.checked) {
            mostrarError('Términos y Condiciones', 'Debes aceptar los términos y condiciones.');
            esValido = false;
        }

        // Si todas las validaciones pasan, se puede proceder al envío del formulario (ej. a un servidor)
        if (esValido) {
            alert('¡Registro exitoso! Se procede con el envío del formulario.');
            // Aquí se enviaría el formulario real, por ejemplo:
            // form.submit();
        }
    });

    // Opcional: Agregar validación en tiempo real a medida que el usuario escribe (por ejemplo, para la contraseña)
    password.addEventListener('input', function() {
        if (validarPassword(this.value)) {
            passwordHint.style.color = 'green';
            passwordHint.textContent = 'Mínimo 8 caracteres, incluir mayúsculas y números ✅';
        } else {
            passwordHint.style.color = 'red';
            passwordHint.textContent = 'Mínimo 8 caracteres, incluir mayúsculas y números ❌';
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 no se cargó. Se mantienen los mensajes de la página.');
        return;
    }

    const mensaje = document.querySelector('.mensaje');

    if (mensaje && mensaje.textContent.trim()) {
        Swal.fire({
            icon: 'success',
            title: 'Listo',
            text: mensaje.textContent.trim(),
            confirmButtonColor: getComputedStyle(document.body)
                .getPropertyValue('--color-modulo')
                .trim() || '#3977e8'
        });
    }

    const bloqueError = document.querySelector('.error');

    if (bloqueError && bloqueError.textContent.trim()) {
        const errores = Array.from(bloqueError.querySelectorAll('li'))
            .map(function (elemento) {
                return elemento.textContent.trim();
            })
            .filter(Boolean);

        Swal.fire({
            icon: 'error',
            title: 'Revisa los datos',
            text: errores.length
                ? errores.join(' ')
                : bloqueError.textContent.trim(),
            confirmButtonColor: '#c0394b'
        });
    }

    document.querySelectorAll('form[data-confirm]').forEach(function (formulario) {
        formulario.addEventListener('submit', function (evento) {
            evento.preventDefault();

            Swal.fire({
                icon: 'question',
                title: '¿Confirmas esta acción?',
                text: formulario.dataset.confirm,
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: getComputedStyle(document.body)
                    .getPropertyValue('--color-modulo')
                    .trim() || '#3977e8',
                cancelButtonColor: '#7b8798'
            }).then(function (resultado) {
                if (resultado.isConfirmed) {
                    formulario.submit();
                }
            });
        });
    });
});
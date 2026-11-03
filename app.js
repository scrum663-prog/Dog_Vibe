document.querySelectorAll('[data-confirm]').
forEach(function (element) { element.addEventListener('click', function () { return confirm(element.dataset.confirm);
 });
 });

// Dog Vibe: muestra los campos adecuados según el rol seleccionado.
(function () {
    const role = document.getElementById('rol');
    const ownerFields = document.getElementById('ownerFields');
    const trainerFields = document.getElementById('trainerFields');
    if (!role || !ownerFields || !trainerFields) return;

    const trainerInputs = trainerFields.querySelectorAll('input, select');

    function updateRoleFields() {
        const isTrainer = role.value === 'Entrenador';
        ownerFields.hidden = isTrainer || role.value !== 'Propietario';
        trainerFields.hidden = !isTrainer;
        trainerInputs.forEach(function (input) {
            input.required = isTrainer;
        });
    }

    role.addEventListener('change', updateRoleFields);
    updateRoleFields();
})();

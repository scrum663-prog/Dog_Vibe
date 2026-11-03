<?php
require_once __DIR__ . '/functions.php';
render_header('Crear cuenta');
?>
<div class="auth-form register-page">
    <div class="form-box register-box">
        <a class="brand form-brand" href="index.php"><img class="logo-img logo-img-login" src="<?= e(base_url() . '/assets/images/dog-vibe-logo.jpg') ?>" alt="Logo Dog Vibe"></a>
        <h2 style="margin-top:28px">Crear una cuenta</h2>
        <p class="muted">Elige tu rol y completa la información solicitada.</p>

        <form action="procesar-registro.php" method="post" enctype="multipart/form-data" id="registerForm">
            <div class="form-grid">
                <label>Nombre completo
                    <input name="nombre" placeholder="Nombre y apellido" required>
                </label>
                <label>Documento de identidad
                    <input name="documento" inputmode="numeric" pattern="[0-9]{6,12}" placeholder="Ej. 1020304050" required>
                </label>
            </div>
            <div class="form-grid">
                <label>Correo electrónico
                    <input type="email" name="correo" placeholder="tu@correo.com" required>
                </label>
                <label>Teléfono
                    <input name="telefono" inputmode="tel" pattern="[0-9 +()-]{7,20}" placeholder="300 000 0000" required>
                </label>
            </div>
            <div class="form-grid">
                <label>Ciudad
                    <input name="ciudad" placeholder="Bogotá" required>
                </label>
                <label>Nombre de usuario
                    <input name="usuario" placeholder="miusuario" required>
                </label>
            </div>
            <label>Contraseña
                <input type="password" name="password" minlength="8" placeholder="Mínimo 8 caracteres" required>
            </label>

            <label>Rol en Dog Vibe
                <select name="rol" id="rol" required>
                    <option value="">Selecciona un rol</option>
                    <option value="Propietario">Propietario</option>
                    <option value="Entrenador">Entrenador</option>
                </select>
            </label>

            <section class="role-fields" id="ownerFields" hidden>
                <div class="role-fields-title">🐾 Información del propietario</div>
                <div class="form-grid">
                    <label>¿Tiene mascotas actualmente?
                        <select name="tiene_mascotas">
                            <option value="Sí">Sí</option><option value="No">No</option>
                        </select>
                    </label>
                    <label>Tipo de relación
                        <select name="relacion_mascota">
                            <option value="">Selecciona</option><option value="Propietario">Soy propietario</option><option value="Cuidador">Soy cuidador</option>
                        </select>
                    </label>
                </div>
                <div class="form-grid">
                    <label>Tipo de mascota principal
                        <select name="tipo_mascota"><option value="">Selecciona</option><option>Perro</option><option>Gato</option><option>Otro</option></select>
                    </label>
                    <label>Teléfono de contacto de emergencia
                        <input name="telefono_emergencia" inputmode="tel" placeholder="300 000 0000">
                    </label>
                </div>
                <p class="field-help">El propietario registra información relacionada con su cuidado y sus mascotas.</p>
            </section>

            <section class="role-fields" id="trainerFields" hidden>
                <div class="role-fields-title">🎓 Verificación profesional del entrenador</div>
                <div class="form-grid">
                    <label>Especialidad
                        <select name="especialidad">
                            <option value="">Selecciona</option>
                            <option>Obediencia básica</option><option>Adiestramiento avanzado</option><option>Agility</option><option>Terapia conductual</option><option>Socialización</option>
                        </select>
                    </label>
                    <label>Años de experiencia
                        <input name="experiencia" type="number" min="0" max="60" placeholder="Ej. 5">
                    </label>
                </div>
                <div class="form-grid">
                    <label>Número de certificación
                        <input name="certificacion" placeholder="Código o número profesional">
                    </label>
                    <label>Tipo de certificación
                        <input name="tipo_certificacion" placeholder="Ej. Curso / Certificado">
                    </label>
                </div>
                <label>Documento profesional / identificación laboral
                    <input name="documento_profesional" placeholder="Número de licencia, registro o documento">
                </label>
                <label>Foto o archivo del certificado (obligatorio)
                    <input type="file" name="archivo_certificado" accept="image/jpeg,image/png,image/webp,application/pdf" data-trainer-required="true">
                </label>
                <p class="field-help">Sube una imagen clara del certificado o un PDF. Máximo 5 MB. Tu perfil quedará pendiente de revisión.</p>
                <p class="field-help">Estos datos permiten una verificación profesional posterior. En una versión con base de datos se pueden validar documentos antes de aprobar el perfil.</p>
            </section>

            <label class="check-label"><input type="checkbox" name="terminos" required> Acepto los términos de uso y autorizo la verificación de los datos suministrados.</label>
            <button class="btn btn-primary full" type="submit">Crear cuenta</button>
        </form>
        <p class="footer-note"><a href="login.php">← Volver al inicio de sesión</a></p>
    </div>
</div>
<script>
const rol=document.getElementById('rol'), ownerFields=document.getElementById('ownerFields'), trainerFields=document.getElementById('trainerFields');
function updateRoleFields(){
 const owner=rol.value==='Propietario', trainer=rol.value==='Entrenador';
 ownerFields.hidden=!owner; trainerFields.hidden=!trainer;
 ownerFields.querySelectorAll('input,select').forEach(el=>el.required=owner && ['tiene_mascotas','relacion_mascota','tipo_mascota'].includes(el.name));
 trainerFields.querySelectorAll('input,select').forEach(el=>el.required=trainer && (['especialidad','experiencia','certificacion','tipo_certificacion','documento_profesional'].includes(el.name) || el.dataset.trainerRequired==='true'));
}
rol.addEventListener('change',updateRoleFields); updateRoleFields();
</script>
</body></html>

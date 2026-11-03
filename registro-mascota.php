<?php
require_once __DIR__.'/functions.php'; require_once __DIR__.'/app_init.php';
require_login(); $pdo=dogvive_db(); $error=dogvive_flash('error'); $ok=dogvive_flash('success');
if ($_SERVER['REQUEST_METHOD']==='POST') {
 try {
  $name=trim((string)($_POST['nombre']??'')); $species=trim((string)($_POST['especie']??'Perro')); $breed=trim((string)($_POST['raza']??''));
  $age=filter_var($_POST['edad']??null,FILTER_VALIDATE_INT); $sex=trim((string)($_POST['sexo']??'')); $weight=filter_var($_POST['peso']??null,FILTER_VALIDATE_FLOAT);
  if (!$name||!$breed||$age===false||$age<0||$age>40||!in_array($sex,['Hembra','Macho','No especificado'],true)) throw new RuntimeException('Revisa nombre, raza, edad y sexo.');
  $photo=dogvive_upload($_FILES['foto']??[], 'mascotas', ['image/jpeg','image/png','image/webp']);
  $stmt=$pdo->prepare('INSERT INTO mascota (id_usuario,Nombre,Especie,Raza,Edad,Sexo,Peso,Color,Microchip,Alergias,Condiciones_medicas,Contacto_veterinario,Observaciones,Foto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
  $stmt->execute([dogvive_user_id(),$name,$species,$breed,(int)$age,$sex,$weight===false?null:$weight,trim((string)($_POST['color']??'')),trim((string)($_POST['microchip']??'')),trim((string)($_POST['alergias']??'')),trim((string)($_POST['salud']??'')),trim((string)($_POST['veterinario']??'')),trim((string)($_POST['observaciones']??'')),$photo]);
  dogvive_notice($pdo,dogvive_user_id(),'Mascota','Registraste a '.$name.' correctamente.', 'mascota-perfil.php?id='.(int)$pdo->lastInsertId());
  dogvive_flash('success','Mascota registrada correctamente. Ahora puedes agregar sus vacunas.'); header('Location: '.base_url().'/mascotas.php'); exit;
 } catch(Throwable $e) { dogvive_flash('error',$e instanceof RuntimeException?$e->getMessage():'No se pudo guardar la mascota. Revisa la conexión de la base de datos.'); header('Location: '.base_url().'/registro-mascota.php'); exit; }
}
start_app('Registro de mascota','mascotas'); page_wrap_start('Registro de mascota','Crea el perfil y expediente básico de tu mascota.');
if($error) echo '<div class="error-box">'.e($error).'</div>'; if($ok) echo '<div class="notice">'.e($ok).'</div>'; ?>
<div class="form-box card" style="max-width:900px"><form method="post" enctype="multipart/form-data">
<div class="form-grid"><label>Nombre<input name="nombre" maxlength="80" required></label><label>Especie<select name="especie"><option>Perro</option><option>Gato</option><option>Otro</option></select></label></div>
<div class="form-grid"><label>Raza<input name="raza" maxlength="80" required></label><label>Edad (años)<input name="edad" type="number" min="0" max="40" required></label></div>
<div class="form-grid"><label>Sexo<select name="sexo" required><option>Hembra</option><option>Macho</option><option>No especificado</option></select></label><label>Peso aproximado (kg)<input name="peso" type="number" min="0" max="250" step="0.1"></label></div>
<div class="form-grid"><label>Color / señas particulares<input name="color"></label><label>Microchip (opcional)<input name="microchip"></label></div>
<label>Foto de la mascota<input type="file" name="foto" accept="image/jpeg,image/png,image/webp"></label>
<div class="form-grid"><label>Alergias<textarea name="alergias" rows="2"></textarea></label><label>Condiciones médicas<textarea name="salud" rows="2"></textarea></label></div>
<div class="form-grid"><label>Veterinario / clínica de contacto<input name="veterinario"></label><label>Observaciones<textarea name="observaciones" rows="2"></textarea></label></div>
<button class="btn btn-primary full" type="submit">Guardar mascota</button></form></div><?php page_wrap_end(); end_app(); ?>

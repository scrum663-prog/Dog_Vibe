<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/app_init.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: '.base_url().'/registro.php'); exit; }
function register_fail(array $errors): never {
    render_header('Error de registro'); echo '<div class="center-page"><div class="card"><h1>No se pudo crear la cuenta</h1><ul class="error-list">';
    foreach ($errors as $error) echo '<li>'.e((string)$error).'</li>';
    echo '</ul><div style="margin-top:20px">'.btn('Volver al registro',page_url('registro'),'btn-primary full').'</div></div></div></body></html>'; exit;
}
$full=trim((string)($_POST['nombre']??'')); $doc=trim((string)($_POST['documento']??'')); $email=strtolower(trim((string)($_POST['correo']??'')));
$phone=trim((string)($_POST['telefono']??'')); $city=trim((string)($_POST['ciudad']??'')); $username=trim((string)($_POST['usuario']??'')); $password=(string)($_POST['password']??''); $role=trim((string)($_POST['rol']??''));
$ownerFields=['tiene_mascotas'=>trim((string)($_POST['tiene_mascotas']??'')),'relacion_mascota'=>trim((string)($_POST['relacion_mascota']??'')),'tipo_mascota'=>trim((string)($_POST['tipo_mascota']??'')),'telefono_emergencia'=>trim((string)($_POST['telefono_emergencia']??''))];
$specialty=trim((string)($_POST['especialidad']??'')); $years=filter_var($_POST['experiencia']??null,FILTER_VALIDATE_INT); $cert=trim((string)($_POST['certificacion']??'')); $certType=trim((string)($_POST['tipo_certificacion']??'')); $professional=trim((string)($_POST['documento_profesional']??''));
$errors=[];
if (mb_strlen($full)<3) $errors[]='Escribe tu nombre completo.';
if (!preg_match('/^[0-9]{6,12}$/',$doc)) $errors[]='El documento debe tener entre 6 y 12 números.';
if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='El correo electrónico no es válido.';
if ($phone===''||$city==='') $errors[]='Completa teléfono y ciudad.';
if (!preg_match('/^[a-zA-Z0-9_.-]{3,60}$/',$username)) $errors[]='El usuario debe tener de 3 a 60 caracteres y usar letras, números, punto, guion o guion bajo.';
if (strlen($password)<8) $errors[]='La contraseña debe tener mínimo 8 caracteres.';
if (!in_array($role,['Propietario','Entrenador'],true)) $errors[]='Selecciona un rol válido.';
if (!isset($_POST['terminos'])) $errors[]='Debes aceptar los términos.';
if ($role==='Propietario' && (!$ownerFields['tiene_mascotas']||!$ownerFields['relacion_mascota']||!$ownerFields['tipo_mascota'])) $errors[]='Completa la información de propietario.';
if ($role==='Entrenador') {
 if (!$specialty||$years===false||$years<0||$years>60||!$cert||!$certType||!$professional) $errors[]='Completa especialidad, experiencia y datos del certificado.';
 if (($_FILES['archivo_certificado']['error']??UPLOAD_ERR_NO_FILE)===UPLOAD_ERR_NO_FILE) $errors[]='Debes adjuntar una foto o archivo del certificado del entrenador.';
}
if ($errors) register_fail($errors);
$parts=preg_split('/\s+/', $full, 2) ?: [$full]; $first=$parts[0]??$full; $last=$parts[1]??''; $certificatePath=null;
try {
 $pdo=dogvive_db();
 $check=$pdo->prepare('SELECT id_usuario FROM usuario WHERE Correo=? OR Usuario=? OR Documento=? LIMIT 1'); $check->execute([$email,$username,$doc]);
 if ($check->fetch()) register_fail(['Ya existe una cuenta con ese correo, usuario o documento.']);
 if ($role==='Entrenador') $certificatePath=dogvive_upload($_FILES['archivo_certificado']??[], 'certificados', ['image/jpeg','image/png','image/webp','application/pdf']);
 $pdo->beginTransaction();
 $sql='INSERT INTO usuario (Nombre,Apellido,Correo,Telefono,`Contraseña`,Direccion,Tipo_usuario,Especialidad,Experiencia,Registro_profesional,Documento,Usuario,Ciudad,Tiene_mascotas,Relacion_mascota,Tipo_mascota,Telefono_emergencia,Tipo_certificacion,Documento_profesional,Estado_verificacion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
 $stmt=$pdo->prepare($sql); $stmt->execute([$first,$last,$email,$phone,password_hash($password,PASSWORD_DEFAULT),$city,$role,$role==='Entrenador'?$specialty:null,$role==='Entrenador'?(int)$years:null,$role==='Entrenador'?$cert:null,$doc,$username,$city,$role==='Propietario'?$ownerFields['tiene_mascotas']:null,$role==='Propietario'?$ownerFields['relacion_mascota']:null,$role==='Propietario'?$ownerFields['tipo_mascota']:null,$role==='Propietario'?$ownerFields['telefono_emergencia']:null,$role==='Entrenador'?$certType:null,$role==='Entrenador'?$professional:null,$role==='Entrenador'?'Pendiente':'Activa']);
 $newId=(int)$pdo->lastInsertId();
 if ($role==='Entrenador') { $c=$pdo->prepare('INSERT INTO certificacion (Nombre,Entidad_emisora,Numero,Fecha_emision,Fecha_vencimiento,id_usuario,Documento_archivo,Estado_revision) VALUES (?,?,?,?,?,?,?,?)'); $c->execute([$certType,'Pendiente de verificación',$cert,null,null,$newId,$certificatePath,'Pendiente']); dogvive_notice($pdo,$newId,'Verificación','Tu certificado fue recibido y está pendiente de revisión.'); }
 $pdo->commit();
 $_SESSION['dogvibe_user']=['id_usuario'=>$newId,'nombre'=>$full,'documento'=>$doc,'correo'=>$email,'telefono'=>$phone,'ciudad'=>$city,'usuario'=>$username,'rol'=>$role,'especialidad'=>$specialty,'experiencia'=>(int)$years,'certificacion'=>$cert,'tipo_certificacion'=>$certType,'documento_profesional'=>$professional];
 $_SESSION['dogvibe_auth']=false; $_SESSION['dogvibe_registration_success']=true;
 header('Location: '.base_url().'/confirmacion-registro.php'); exit;
} catch (Throwable $e) {
 if (isset($pdo)&&$pdo instanceof PDO&&$pdo->inTransaction()) $pdo->rollBack();
 if ($certificatePath && is_file(__DIR__.'/'.$certificatePath)) @unlink(__DIR__.'/'.$certificatePath);
 error_log('DogVive registration: '.$e->getMessage());
 register_fail([$e instanceof RuntimeException ? $e->getMessage() : 'No fue posible guardar la cuenta. Verifica MySQL, la base dog_next y las columnas existentes. Si continúa, revisa el registro de errores de PHP.']);
}

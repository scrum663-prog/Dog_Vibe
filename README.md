# Dog Vive 1.1 — PHP + MySQL/MariaDB

Esta versión conserva la estructura y estilos principales de Dog Vive 1.1 y conecta los formularios principales con la base de datos.

## Instalación en XAMPP

1. Haz una copia de seguridad de tu carpeta y base de datos actuales.
2. Copia `DogVive1.1` dentro de `C:\xampp\htdocs\DogVive1.1`.
3. Abre XAMPP e inicia **Apache** y **MySQL**.
4. Entra a phpMyAdmin y comprueba que exista la base `dog_next` (o ajusta el nombre en `conexion.php`).
5. Si la base ya existe, **no importes otra vez el SQL original sobre ella**. Las columnas y tablas auxiliares que faltan se crean al entrar a una función conectada.
6. Si la base todavía no existe, importa una sola vez el SQL de referencia original, `DOG_Vibe_phpMyAdmin_referencia_NO_REIMPORTAR.sql`.
7. Abre `http://localhost/DogVive1.1/`.

## Conexión

Archivo: `conexion.php`.

- Host: `localhost`
- Base de datos: `dog_next`
- Usuario: `root`
- Contraseña: vacía por defecto en XAMPP

Si tu instalación usa otra contraseña o nombre de base, cambia esos valores en `conexion.php`.

## Funciones conectadas

- Registro de propietarios y entrenadores; las contraseñas se almacenan con `password_hash()`.
- Carga obligatoria de certificado para entrenadores, con límite de 5 MB y comprobación del tipo de archivo.
- Inicio y cierre de sesión, con registro de eventos.
- Perfil de cuenta y actualización de datos personales.
- Registro de mascotas, datos de salud y fotos opcionales.
- Carnet de vacunas con fecha de aplicación, próxima dosis y comprobante opcional.
- Búsqueda de entrenadores registrados y perfiles dinámicos.
- Solicitud de servicio/reserva, cancelación y cambios de estado por el entrenador.
- Mensajes entre cuentas, notificaciones, PQRSD, seguimiento y reseñas.
- Restablecimiento de contraseña con enlace de un solo uso y expiración. Para enviar correos fuera de localhost, configura `mail()` en PHP o integra un proveedor de correo.
- Registro de intención de pago. **No procesa cobros reales** ni solicita números de tarjeta o CVV; para pagos reales se debe integrar una pasarela certificada.

## Importante

- Las cuentas antiguas de demostración pueden tener contraseñas de texto plano. Al iniciar sesión correctamente, el sistema intenta convertirlas a hash. Cambia las contraseñas de prueba antes de publicar el sitio.
- El certificado del entrenador queda pendiente de verificación; cargar un documento no significa que el entrenador ya esté certificado.
- El calendario de vacunas es un registro administrativo y no reemplaza las indicaciones del veterinario.
- Antes de publicar en internet, configura HTTPS, correo transaccional, copias de seguridad, controles de acceso a documentos privados y una pasarela de pagos real si se necesita cobrar.
- Si una página muestra un error de base de datos, revisa Apache/PHP y MySQL en XAMPP y el log de errores de PHP.

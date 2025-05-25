<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documentación API - Sistema de Gestión Médica</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <h2>📘 API Docs</h2>
      <nav>
        <a href="#url-base">🔗 URL Base</a>
        <a href="#auth">🔐 Autenticación</a>
        <a href="#listar">👤 Listar Pacientes</a>
        <a href="#obtener">👤 Obtener Paciente</a>
        <a href="#crear">➕ Crear Paciente</a>
        <a href="#actualizar">✏️ Actualizar Paciente</a>
        <a href="#eliminar">🗑️ Eliminar Paciente</a>
        <a href="#errores">❗ Errores Comunes</a>
        <a href="#postman">🧪 Pruebas</a>
      </nav>
    </aside>
    <main class="main">
      <section id="url-base">
        <h1>📘 Documentación de la API - Sistema de Gestión Médica</h1>
        <h2>🔗 URL Base</h2>
        <div class="code-block">
<pre><code id="url">http://localhost/api/</code></pre>
          <button onclick="copyToClipboard('url')">Copiar</button>
        </div>
      </section>

      <section id="auth">
        <h2>🔐 Autenticación</h2>
        <p><strong>POST /auth</strong>: Inicia sesión y retorna un token de autenticación.</p>
        <h4>📥 Body (JSON):</h4>
        <div class="code-block">
<pre><code id="auth-body">{
  "usuario": "correo@ejemplo.com",
  "password": "clave123"
}</code></pre>
          <button onclick="copyToClipboard('auth-body')">Copiar</button>
        </div>
        <h4>📤 Respuesta:</h4>
        <code>{ "status": "ok", "result": { "token": "..." } }</code>
      </section>

      <section id="listar">
        <h2>👤 Listar Pacientes</h2>
        <p><strong>GET /pacientes?page=1</strong></p>
        <h4>🔒 Headers:</h4>
        <code>token: TU_TOKEN</code>
        <h4>📤 Respuesta:</h4>
        <code>[ { "PacienteId": "1", "Nombre": "Juan Pérez", ... } ]</code>
      </section>

      <section id="obtener">
        <h2>👤 Obtener un Paciente</h2>
        <p><strong>GET /pacientes?id=1</strong></p>
        <h4>🔒 Headers:</h4>
        <code>token: TU_TOKEN</code>
        <h4>📤 Respuesta:</h4>
        <code>[ { "PacienteId": "1", "Nombre": "Juan Pérez" } ]</code>
      </section>

      <section id="crear">
        <h2>➕ Crear Paciente</h2>
        <p><strong>POST /pacientes</strong></p>
        <h4>📥 Body:</h4>
        <div class="code-block">
<pre><code id="crear-body">{
  "token": "TU_TOKEN",
  "nombre": "Juan Pérez",
  "dni": "12345678",
  "correo": "juan@example.com",
  "telefono": "3001234567",
  "direccion": "Calle Falsa 123",
  "codigoPostal": "110111",
  "genero": "M",
  "fechaNacimiento": "1990-05-01"
}</code></pre>
          <button onclick="copyToClipboard('crear-body')">Copiar</button>
        </div>
      </section>

      <section id="actualizar">
        <h2>✏️ Actualizar Paciente</h2>
        <p><strong>PUT /pacientes</strong></p>
        <h4>📥 Body:</h4>
        <div class="code-block">
<pre><code id="actualizar-body">{
  "token": "TU_TOKEN",
  "pacienteId": "1",
  "nombre": "Juan Actualizado",
  "telefono": "3216549870"
}</code></pre>
          <button onclick="copyToClipboard('actualizar-body')">Copiar</button>
        </div>
      </section>

      <section id="eliminar">
        <h2>🗑️ Eliminar Paciente</h2>
        <p><strong>DELETE /pacientes</strong></p>
        <h4>📥 Body:</h4>
        <div class="code-block">
<pre><code id="eliminar-body">{
  "token": "TU_TOKEN",
  "pacienteId": "1"
}</code></pre>
          <button onclick="copyToClipboard('eliminar-body')">Copiar</button>
        </div>
      </section>

      <section id="errores">
        <h2>❗ Errores Comunes</h2>
        <table>
          <thead>
            <tr><th>Código</th><th>Significado</th><th>Detalle</th></tr>
          </thead>
          <tbody>
            <tr><td>200</td><td>Error de lógica</td><td>Credenciales inválidas</td></tr>
            <tr><td>400</td><td>Datos incompletos</td><td>Campos mal formateados</td></tr>
            <tr><td>401</td><td>No autorizado</td><td>Token ausente o inválido</td></tr>
            <tr><td>405</td><td>Método no permitido</td><td>Método HTTP incorrecto</td></tr>
            <tr><td>500</td><td>Error interno</td><td>Fallo del servidor</td></tr>
          </tbody>
        </table>
      </section>

      <section id="postman">
        <h2>🧪 Prueba con Postman</h2>
        <ol>
          <li>Inicia sesión con <code>/auth</code> y guarda el token.</li>
          <li>En cada petición agrega el token en el header: <code>token: TU_TOKEN</code>.</li>
          <li>Para POST, PUT y DELETE usa <em>raw → JSON</em>.</li>
        </ol>
      </section>
    </main>
  </div>
  <script>
    function copyToClipboard(id) {
      const text = document.getElementById(id).innerText;
      navigator.clipboard.writeText(text).then(() => {
        alert("¡Copiado al portapapeles!");
      });
    }
  </script>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../db/config.php';

if (!isset($_SESSION['ROL']) || $_SESSION['ROL'] !== 'admin') {
  header('Location: ../login.php');
  exit();
}
// Eliminar usuario
if (isset($_GET['delete_user'])) {
  $userId = $_GET['delete_user'];

  // Eliminar registros relacionados en tablas dependientes
  $stmt = $pdo->prepare("DELETE FROM administrador WHERE ID_USUARIO_FK = :id");
  $stmt->execute(['id' => $userId]);

  $stmt = $pdo->prepare("DELETE FROM adopciones WHERE ID_USUARIO_FK = :id");
  $stmt->execute(['id' => $userId]);

  $stmt = $pdo->prepare("DELETE FROM publicaciones WHERE usuario_id = :id");
  $stmt->execute(['id' => $userId]);

  // Finalmente eliminar el usuario
  $stmt = $pdo->prepare("DELETE FROM usuarios WHERE ID_USUARIO = :id");
  $stmt->execute(['id' => $userId]);

  header("Location: admin_dashboard.php?");
  exit();
}

// Cambiar estado de adopción
if (isset($_POST['update_adoption'])) {
  $stmt = $pdo->prepare("UPDATE adopciones SET ID_ESTADO_FK = :estado WHERE `ID-SOLICITUD` = :id");
  $stmt->execute(['estado' => $_POST['estado'], 'id' => $_POST['solicitud_id']]);
  header("Location: admin_dashboard.php?adopcion_actualizada=1");
  exit();
}

// Cambiar rol del usuario
if (isset($_POST['update_role'])) {
  $stmt = $pdo->prepare("UPDATE usuarios SET ROL = :rol WHERE ID_USUARIO = :id");
  $stmt->execute([
    'rol' => $_POST['new_role'],
    'id' => $_POST['user_id']
  ]);
  header("Location: admin_dashboard.php?rol_actualizado=1");
  exit();
}

$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll();
$publicaciones = $pdo->query("SELECT p.*, u.NOMBRES, u.APELLIDOS FROM publicaciones p JOIN usuarios u ON p.usuario_id = u.ID_USUARIO ORDER BY fecha DESC LIMIT 10")->fetchAll();
$adopciones = $pdo->query("SELECT a.`ID-SOLICITUD` AS ID, u.NOMBRES, u.APELLIDOS, m.ESPECIE, e.ESTADO, a.CIUDAD, a.FECHA
FROM adopciones a
JOIN usuarios u ON a.ID_USUARIO_FK = u.ID_USUARIO
JOIN mascotas m ON a.ID_MASCOTA_FK = m.ID_MASCOTAS
JOIN estado e ON a.ID_ESTADO_FK = e.ESTADO
ORDER BY a.FECHA DESC")->fetchAll();
$estados = $pdo->query("SELECT ESTADO FROM estado")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Pet Friend</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function confirmarEliminacion(url) {
      if (confirm('¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.')) {
        window.location.href = url;
      }
    }
  </script>
</head>
<body>
<div class="d-flex">
  <nav class="p-3 bg-dark text-white" style="min-width: 200px; height: 100vh;">
    <h4>Administrador</h4>
    <ul class="nav flex-column">
      <li class="nav-item"><a href="#usuarios" class="nav-link text-white">Usuarios</a></li>
      <li class="nav-item"><a href="#publicaciones" class="nav-link text-white">Publicaciones</a></li>
      <li class="nav-item"><a href="#adopciones" class="nav-link text-white">Adopciones</a></li>
      <a class="nav-link text-white" href="logout.php">Cerrar sesión</a>
    </ul>
  </nav>
  <div class="flex-grow-1 p-4">
    <h2 class="mb-4">Panel de Administración</h2>

   <!-- Alertas -->
     <?php if (isset($_GET['delete_user'])): ?>
       <div class="alert alert-success alert-dismissible fade show" role="alert">
         ✅El usuario fue eliminado exitosamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      <?php endif; ?>

    <?php if (isset($_GET['rol_actualizado'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ El rol del usuario se actualizó correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
      </div>
    <?php endif; ?>
    <?php if (isset($_GET['adopcion_actualizada'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ Estado de adopción actualizado.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
      </div>
    <?php endif; ?>

    <!-- Secciones -->
    <section id="usuarios">
      <h4>Usuarios Registrados</h4>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Contraseña</th>
            <th>Edad</th>
            <th>Celular</th>
            <th>Ciudad</th>
            <th>Documento</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?= $u['ID_USUARIO'] ?></td>
              <td><?= htmlspecialchars($u['NOMBRES']) ?></td>
              <td><?= htmlspecialchars($u['APELLIDOS']) ?></td>
              <td><?= htmlspecialchars($u['CORREO']) ?></td>
              <td>••••••••</td>
              <td><?= htmlspecialchars($u['EDAD']) ?></td>
              <td><?= htmlspecialchars($u['CELULAR']) ?></td>
              <td><?= htmlspecialchars($u['CIUDAD']) ?></td>
              <td><?= htmlspecialchars($u['IDENTIFICACION']) ?></td>
              <td>
                <form method="POST" class="d-flex">
                  <input type="hidden" name="user_id" value="<?= $u['ID_USUARIO'] ?>">
                  <select name="new_role" class="form-select form-select-sm me-2">
                    <option value="usuario" <?= $u['ROL'] === 'usuario' ? 'selected' : '' ?>>usuario</option>
                    <option value="admin" <?= $u['ROL'] === 'admin' ? 'selected' : '' ?>>admin</option>
                  </select>
                  <button type="submit" name="update_role" class="btn btn-sm btn-primary">Cambiar</button>
                </form>
              </td>
              <td>
                <button onclick="confirmarEliminacion('?delete_user=<?= $u['ID_USUARIO'] ?>')" class="btn btn-danger btn-sm">Eliminar</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Publicaciones -->
    <section id="publicaciones" class="mt-5">
      <h4>Publicaciones Recientes</h4>
      <div class="row">
        <?php foreach ($publicaciones as $p): ?>
          <div class="col-md-6 mb-3">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($p['titulo']) ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">Por <?= htmlspecialchars($p['NOMBRES'] . ' ' . $p['APELLIDOS']) ?> | Estado: <?= htmlspecialchars($p['estado']) ?></h6>
                <p class="card-text"><?= nl2br(htmlspecialchars($p['contenido'])) ?></p>
                
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Adopciones -->
    <section id="adopciones" class="mt-5">
      <h4>Solicitudes de Adopción</h4>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Mascota</th>
            <th>Estado</th>
            <th>Ciudad</th>
            <th>Fecha</th>
            <th>Cambiar Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($adopciones as $a): ?>
            <?php
              $badge = match($a['ESTADO']) {
                'APROBADA' => 'success',
                'EN CURSO' => 'warning',
                'RECHAZADA' => 'danger',
                default => 'secondary',
              };
            ?>
            <tr>
              <td><?= $a['ID'] ?></td>
              <td><?= htmlspecialchars($a['NOMBRES'] . ' ' . $a['APELLIDOS']) ?></td>
              <td><?= htmlspecialchars($a['ESPECIE']) ?></td>
              <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($a['ESTADO']) ?></span></td>
              <td><?= htmlspecialchars($a['CIUDAD']) ?></td>
              <td><?= htmlspecialchars($a['FECHA']) ?></td>
              <td>
                <form method="POST" class="d-flex">
                  <input type="hidden" name="solicitud_id" value="<?= $a['ID'] ?>">
                  <select name="estado" class="form-select form-select-sm me-2">
                    <?php foreach ($estados as $e): ?>
                      <option value="<?= $e ?>" <?= $e === $a['ESTADO'] ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" name="update_adoption" class="btn btn-sm btn-primary">Actualizar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function confirmarEliminacion(url) {
    if (confirm('¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.')) {
      window.location.href = url;
    }
  }
</script>
</body>
</html>

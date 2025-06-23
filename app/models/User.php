<?php
class User extends Model
{
    public function getAll()
    {
        $stmt = $this->getDB()->query("SELECT * FROM usuarios");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO usuarios 
                (NOMBRES, APELLIDOS, CORREO, IDENTIFICACION, EDAD, CIUDAD, CONTRASEÑA, ROL)
                VALUES 
                (:nombre, :apellido, :correo, :documento, :edad, :ciudad, :contrasena, :rol)";

        $stmt = $this->getDB()->prepare($sql);

        return $stmt->execute([
            ':nombre'     => $data['nombre'],
            ':apellido'   => $data['apellido'],
            ':correo'     => $data['correo'],
            ':edad'       => $data['edad'],
            ':documento'  => $data['documento'],
            ':ciudad'     => $data['ciudad'],
            ':contrasena' => $data['contrasena'],
            ':rol'        => $data['rol'],
        ]);
    }

    public function findByCorreo($correo)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM usuarios WHERE CORREO = :correo LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM usuarios WHERE ID_USUARIO = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateFoto($id, $nombreArchivo)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET FOTO = ? WHERE ID_USUARIO = ?");
        $stmt->execute([$nombreArchivo, $id]);
    }
}

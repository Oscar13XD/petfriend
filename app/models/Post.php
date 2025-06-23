<?php
class Post extends Model
{
    public function getByUser($userId, $status = null, $excludeStatus = null)
    {
        if ($status) {
            $stmt = $this->getDB()->prepare("
            SELECT * FROM publicaciones
            WHERE usuario_id = ? AND estado = ?
            ORDER BY fecha DESC
        ");
            $stmt->execute([$userId, $status]);
        } elseif ($excludeStatus) {
            $stmt = $this->getDB()->prepare("
            SELECT * FROM publicaciones
            WHERE usuario_id = ? AND estado != ?
            ORDER BY fecha DESC
        ");
            $stmt->execute([$userId, $excludeStatus]);
        } else {
            $stmt = $this->getDB()->prepare("
            SELECT * FROM publicaciones
            WHERE usuario_id = ?
            ORDER BY fecha DESC
        ");
            $stmt->execute([$userId]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

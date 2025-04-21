<?php
namespace App\Models;

use App\Core\Model;

class Category extends Model {
    protected $table = 'categories';

    public function getAllCategories() {
        $sql = "SELECT id, name, slug, image, description FROM {$this->table} WHERE status = 1 ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getCategoryById($id) {
        $sql = "SELECT id, name, slug, image, description FROM {$this->table} WHERE id = :id AND status = 1";
        return $this->db->query($sql, ['id' => $id])->fetch();
    }

    public function getCategoryBySlug($slug) {
        $sql = "SELECT id, name, slug, image, description FROM {$this->table} WHERE slug = :slug AND status = 1";
        return $this->db->query($sql, ['slug' => $slug])->fetch();
    }
} 
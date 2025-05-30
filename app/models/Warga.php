<?php
require_once '../app/core/Database.php';

class Warga {
    private $conn;
    private $table_name = "warga";

    public $warga_id;
    public $nik;
    public $nama_lengkap;
    public $jenis_kelamin;
    public $alamat;
    public $rt;
    public $rw;
    public $phone;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nik=:nik, nama_lengkap=:nama_lengkap, jenis_kelamin=:jenis_kelamin, 
                      alamat=:alamat, rt=:rt, rw=:rw, phone=:phone";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nik", $data['nik']);
        $stmt->bindParam(":nama_lengkap", $data['nama_lengkap']);
        $stmt->bindParam(":jenis_kelamin", $data['jenis_kelamin']);
        $stmt->bindParam(":alamat", $data['alamat']);
        $stmt->bindParam(":rt", $data['rt']);
        $stmt->bindParam(":rw", $data['rw']);
        $stmt->bindParam(":phone", $data['phone']);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_lengkap";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE warga_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByNik($nik) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nik = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nik);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nama_lengkap=:nama_lengkap, jenis_kelamin=:jenis_kelamin, 
                      alamat=:alamat, rt=:rt, rw=:rw, phone=:phone 
                  WHERE warga_id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nama_lengkap", $data['nama_lengkap']);
        $stmt->bindParam(":jenis_kelamin", $data['jenis_kelamin']);
        $stmt->bindParam(":alamat", $data['alamat']);
        $stmt->bindParam(":rt", $data['rt']);
        $stmt->bindParam(":rw", $data['rw']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
}
?>
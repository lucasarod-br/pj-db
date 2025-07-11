<?php
require_once __DIR__ . '/../config/database.php';

class EventoRepository {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO Evento (nome, descricao, data_inicio, data_fim, id_categoria, id_local) 
                VALUES (:nome, :descricao, :data_inicio, :data_fim, :id_categoria, :id_local)";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':data_inicio', $dados['data_inicio']);
        $stmt->bindParam(':data_fim', $dados['data_fim']);
        $stmt->bindParam(':id_categoria', $dados['id_categoria']);
        $stmt->bindParam(':id_local', $dados['id_local']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    public function listar() {
        $sql = "SELECT e.*, c.nome as categoria_nome, l.nome as local_nome, l.campus, l.sala 
                FROM Evento e 
                LEFT JOIN Categoria c ON e.id_categoria = c.id_categoria 
                LEFT JOIN Local l ON e.id_local = l.id_local
                ORDER BY e.data_inicio DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT e.*, c.nome as categoria_nome, l.nome as local_nome, l.campus, l.sala 
                FROM Evento e 
                LEFT JOIN Categoria c ON e.id_categoria = c.id_categoria 
                LEFT JOIN Local l ON e.id_local = l.id_local
                WHERE e.id_evento = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function atualizar($id, $dados) {
        $sql = "UPDATE Evento SET 
                nome = :nome, 
                descricao = :descricao, 
                data_inicio = :data_inicio, 
                data_fim = :data_fim, 
                id_categoria = :id_categoria, 
                id_local = :id_local 
                WHERE id_evento = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':data_inicio', $dados['data_inicio']);
        $stmt->bindParam(':data_fim', $dados['data_fim']);
        $stmt->bindParam(':id_categoria', $dados['id_categoria']);
        $stmt->bindParam(':id_local', $dados['id_local']);
        
        return $stmt->execute();
    }
    
    public function deletar($id) {
        $sql = "DELETE FROM Evento WHERE id_evento = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    public function listarCategorias() {
        $sql = "SELECT * FROM Categoria ORDER BY nome";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarLocais() {
        $sql = "SELECT * FROM Local ORDER BY nome";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

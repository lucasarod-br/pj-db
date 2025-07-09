<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "Conexão com MySQL estabelecida com sucesso!";
    } else {
        echo "Falha na conexão";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
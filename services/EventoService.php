<?php
require_once __DIR__ . '/../repository/EventoRepository.php';

class EventoService {
    private $eventoRepository;
    
    public function __construct() {
        $this->eventoRepository = new EventoRepository();
    }
    
    public function criarEvento($dados) {
        // Validações
        if (empty($dados['nome']) || empty($dados['data_inicio']) || empty($dados['data_fim'])) {
            throw new Exception("Nome, data de início e data de fim são obrigatórios");
        }
        
        if ($dados['data_inicio'] > $dados['data_fim']) {
            throw new Exception("A data de início não pode ser maior que a data de fim");
        }
        
        if ($dados['data_inicio'] < date('Y-m-d')) {
            throw new Exception("A data de início não pode ser anterior a hoje");
        }
        
        return $this->eventoRepository->criar($dados);
    }
    
    public function listarEventos() {
        return $this->eventoRepository->listar();
    }
    
    public function buscarEventoPorId($id) {
        if (empty($id)) {
            throw new Exception("ID é obrigatório");
        }
        
        $evento = $this->eventoRepository->buscarPorId($id);
        if (!$evento) {
            throw new Exception("Evento não encontrado");
        }
        
        return $evento;
    }
    
    public function atualizarEvento($id, $dados) {
        if (empty($id)) {
            throw new Exception("ID é obrigatório");
        }
        
        // Verificar se o evento existe
        $evento = $this->eventoRepository->buscarPorId($id);
        if (!$evento) {
            throw new Exception("Evento não encontrado");
        }
        
        // Validações
        if (empty($dados['nome']) || empty($dados['data_inicio']) || empty($dados['data_fim'])) {
            throw new Exception("Nome, data de início e data de fim são obrigatórios");
        }
        
        if ($dados['data_inicio'] > $dados['data_fim']) {
            throw new Exception("A data de início não pode ser maior que a data de fim");
        }
        
        return $this->eventoRepository->atualizar($id, $dados);
    }
    
    public function deletarEvento($id) {
        if (empty($id)) {
            throw new Exception("ID é obrigatório");
        }
        
        // Verificar se o evento existe
        $evento = $this->eventoRepository->buscarPorId($id);
        if (!$evento) {
            throw new Exception("Evento não encontrado");
        }
        
        return $this->eventoRepository->deletar($id);
    }
    
    public function obterCategorias() {
        return $this->eventoRepository->listarCategorias();
    }
    
    public function obterLocais() {
        return $this->eventoRepository->listarLocais();
    }
    
    public function obterEventosProximos() {
        $eventos = $this->eventoRepository->listar();
        
        $eventosProximos = array_filter($eventos, function($evento) {
            return $evento['data_inicio'] >= date('Y-m-d');
        });
        
        return array_slice($eventosProximos, 0, 5);
    }
    
    public function contarEventos() {
        $eventos = $this->eventoRepository->listar();
        return count($eventos);
    }
}
?>

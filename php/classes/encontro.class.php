<?php

class Encontro {
    private $props = [];
    public $valores_atualizar = array();
    
    public function cadastrar() {
        $id = DBcreate("encontros", $this->toArray());
        $this->id = $id;
        return array('estado'=>1, 'mensagem'=>"Encontro cadastrado com sucesso!");
    }
    
    public function inscrever($usuario) {
        DBupdate("encontro_inscritos", array('presente'=>1), "where id_usuario={$usuario} and id_encontro={$this->id}");
        
        return array('estado'=>1, 'mensagem'=>"Presença confirmada com sucesso!");
    }
    
    public function pagar($usuario) {
        $pagamento = array(
            'id_usuario'=> $usuario,
            'id_encontro'=> $this->id,
            'estado'=> 3,
            'estado'=> 3,
            'tipo'=> 3,
            'time'=> time(),
            'descricao'=> "Inscrição do usuário de ID {$usuario} no encontro de ID {$this->id}",
            'valor'=> $this->valor
        );
        
        $id = DBcreate("pagamento", $pagamento);
        $pagamento['id'] = $id;
        
        $inscricao = array(
            'id_usuario'=> $usuario,
            'id_encontro'=> $this->id,
            'time'=> time(),
            'presente'=> 0
        );
        
        $id = DBcreate("encontro_inscritos", $inscricao);
        $inscricao['id'] = $id;
        
        return array('estado'=>1, 'mensagem'=>"Pagamento confirmado com sucesso!", 'pagamento'=>$pagamento, 'inscricao'=> $inscricao);
    }
    
    public function atualizar() {
        $temp = $this->id;
        DBupdate("encontros", $this->valores_atualizar, "where id={$temp}");
        $this->valores_atualizar = array();
         
        return array('estado'=>1, 'mensagem'=>"Encontro atualizado com sucesso!");
    }
    
    public function excluir() {
        DBdelete("encontros", "where id={$this->id}");
            
        return array('estado'=>1, 'mensagem'=>"Encontro removido com sucesso!");
    }
    
    public function toArray() {
        return $this->props;
    }
    
    public function fromArray($post) {
        foreach($post as $key => $value) {
            $this->props[$key] = $value;
            $this->valores_atualizar[$key] = $value;
        }
    }
    
    // Gets e Sets
    public function __get($name) {
        if (isset($this->props[$name])) {
            return $this->props[$name];
        } else {
            return false;
        }
    }

    public function __set($name, $value) {
        $this->props[$name] = $value;
        $this->valores_atualizar[$name] = $value;
    }
    
    public function __wakeup(){
        foreach (get_object_vars($this) as $k => $v) {
            $this->{$k} = $v;
        }
    }
    
    public function __construct($dados=null) {
        if ($dados!=null) {
            $this->fromArray($dados);
        }
    }
}
?>
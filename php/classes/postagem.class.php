<?php

class postagem {
    private $props = [];
    public $valores_atualizar = array();
    
    public function nova () {
        $this->time = time();
        $this->id = DBcreate('postagem', $this->toArray());

        return array('estado'=>1, 'mensagem'=>"Postagem criada com sucesso!", 'postagem'=> $this->toArray());
    }
    
    public function atualizar () {
        DBupdate('postagem', $this->toArray(), "where id={$this->id}");

        return array('estado'=>1, 'mensagem'=>"Postagem alterada!", 'postagem'=>$this->toArray());
    }

    public function carregar($area_atuacao, $atua_como) {
        $postagens = DBselect('postagem p INNER JOIN usuario u ON p.id_usuario = u.id', "where p.area_atuacao = '{$area_atuacao}' and p.atua_como = {$atua_como}", "p.*, u.foto_perfil, u.nome, u.cidade, u.estado, (select COUNT(id) from postagem_resposta where id_postagem = p.id) respostas");

        $respostas = DBselect("postagem_resposta r INNER JOIN usuario u ON r.id_usuario = u.id", "where id_postagem in (select id from postagem where area_atuacao = '{$area_atuacao}' and atua_como = {$atua_como}) order by id_postagem DESC, time ASC", 'r.*, u.nome, u.cidade, u.estado, u.foto_perfil, u.id as id_usuario');

        return array('postagens'=>$postagens, 'respostas'=>$respostas);
    }

    public function excluir() {
        DBdelete('postagem', "where id={$this->id}");
        DBdelete('postagem_resposta', "where id_postagem={$this->id}");

        return array('estado'=>1, 'mensagem'=>"Postagem excluida!", 'postagem'=>$this->toArray());
    }

    public function responder () {
        $this->time = time();
        $this->id = DBcreate('postagem_resposta', $this->toArray());

        return array('estado'=>1, 'mensagem'=>"Reposta criada!", 'resposta'=> $this->toArray());
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

    public function unsetAtributo($chave) {
        unset($this->props[$chave]);
        unset($this->valores_atualizar[$chave]);
    }
    
    public function estaDeclarado($chave) {
        if (isset($this->props[$chave])) return true;
        else return false;
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
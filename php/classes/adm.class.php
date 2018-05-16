<?php

class adm {
    private $props = [];
    public $valores_atualizar = array();
    
    public function cadastrar() {
        $senha_digitada = $this->senha;
        $this->hash = time();
        $this->senha = md5($senha_digitada.$this->hash);
                    
        $dados = array_filter($this->toArray());
        DBcreate('adm', $dados);

        return array('estado'=>4, 'mensagem'=>"Cadastro conta adm realizado com sucesso, faça login para continuar!");
    }
    
    public function login() {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $existe = DBselect("adm");
            
            if (!isset($existe)) {
                return $this->cadastrar();
                exit;
            }
            
            $result = DBselect('adm', "where email = '{$this->email}'");
            
            // Verifica se usuário está cadastrado
            if (!empty($result)) {        
                $result = $result[0];
                // Verifica se senha está correta
                if ($result['senha'] == md5($this->senha.$result['hash'])) {
                    unset($_SESSION['usuario']);
                    // echo "usuario encontrado";

                    // CAPTURAR TODAS AS INFORMAÇÕES DO ADM
                    $dados = $result;
                    $this->fromArray($dados);
                    $this->valores_atualizar = array();

                    // TEMPO PARA EXPIRAR SESSÃO
                    $_SESSION['expire'] = time();
                    $_SESSION['tipo_usuario']=2;
                    if ($this->lembrar==true) {
                        $_SESSION['lembrar'] = 1;
                    } else {
                        $_SESSION['lembrar'] = 0;
                    }

                    // IDENTIFICAR SESSÃO
                    $_SESSION['donoSessao']=md5('sat'.$_SESSION['expire']);
                    $_SESSION['adm'] = "teste";
                    session_name($_SESSION['donoSessao']);

                    $_SESSION['usuario'] = serialize($this);
                    return array('estado'=>4, 'mensagem'=> "OK");
                } else {
                    return array('estado'=>3, 'mensagem'=> "Senha incorreta para esta conta!");
                }
            } else {
                return array('estado'=>2, 'mensagem'=> "Credenciais inválidas!");
            }
        } else {
            return array('estado'=>2, 'mensagem'=> "Digite um email válido");
        }
    }
    
    public function atualizar() {
        $temp = $this->id;
        DBupdate("usuario", $this->valores_atualizar, "where id={$temp}");
        $this->valores_atualizar = array();
        
        return array('estado'=>1, 'mensagem'=>"Usuario atualizada com sucesso!");
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
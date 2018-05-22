<?php

class usuario {
    private $props = [];
    public $valores_atualizar = array();
    
    public function cadastrar($arquivos = null) {
        $senha_digitada = $this->senha;
        
        $result = DBselect('usuario', "where email = '{$this->email}'");
        
        if (isset($result)) {
            $result = $result[0];
            if ($this->email==$result['email']) {
                return array('estado'=>2, 'mensagem'=>"Já existe algum usuário cadastrado com esse email!");
            } else if ($this->telefone==$result['telefone']) {
                return array('estado'=>2, 'mensagem'=>"Já existe algum usuário cadastrado com esse telefone!");
            }
        } else {
            $dados = array_filter($this->toArray());
            unset($dados['repetirSenha']);

            $this->data_criacao = time();

            if ($arquivos!=null and is_uploaded_file($arquivos['foto_perfil']['tmp_name'])) {
                $this->foto_perfil = $this->mudarFoto($arquivos['foto_perfil'], $this->larguraImagem, $this->alturaImagem);
                $this->unsetAtributo('alturaImagem');
                $this->unsetAtributo('larguraImagem');
            }

            DBcreate('usuario', $this->toArray());
            
            return array('estado'=>1, 'mensagem'=>"Em sua Conta vc poderá deixar suas informações visíveis ou não para outros Participante, porém algumas já estarão visíveis.");
        }
    }
    
    public function login() {
        $result = DBselect('usuario', "where email = '{$this->email}'");
        if (count($result)>0 and (array_key_exists('estado_conta', $result[0]) || filter_var($this->email, FILTER_VALIDATE_EMAIL))) {
            $result = $result[0];
            // Verifica se usuário está cadastrado
            if (!empty($result)) {        
                // Verifica se senha está correta
                if ($result['senha'] == $this->senha) {
                    
                    if ($result['estado_conta']==0) {
                        return array('estado'=>10, 'mensagem'=> "Redirecionando!", 'id' => $result['id']);
                    } else if ($result['estado_conta']==1) {
                        unset($_SESSION['usuario']);
                        // echo "usuario encontrado";

                        // CAPTURAR TODAS AS INFORMAÇÕES DO usuario
                        $dados = $result;
                        $this->fromArray($dados);
                        $this->valores_atualizar = array();
                        
                        // TEMPO PARA EXPIRAR SESSÃO
                        $_SESSION['expire'] = time();
                        $_SESSION['tipo_usuario']=1;
                        if ($this->lembrar==true) {
                            $_SESSION['lembrar'] = 1;
                        } else {
                            $_SESSION['lembrar'] = 0;
                        }

                        // IDENTIFICAR SESSÃO
                        $_SESSION['donoSessao']=md5('sat'.$_SESSION['expire']);
                        session_name($_SESSION['donoSessao']);

                        $_SESSION['usuario'] = serialize($this);
                        return array('estado'=>1, 'mensagem'=> "OK");
                    }
                } else {
                    return array('estado'=>2, 'mensagem'=> "Senha incorreta para esta conta!");
                }
            } else {
                return array('estado'=>2, 'mensagem'=> "Credenciais inválidas!");
            }
        } else {
            return array('estado'=>2, 'mensagem'=> "Email inexistente!");
        }
    }

    public function carregar($id) {
        $result = DBselect('usuario', "where id={$id}");

        return $result;
    }

    public function mensagem() {
        $this->time = time();
        DBcreate('usuario_mensagem', $this->toArray());

        return array('estado'=>1, 'mensagem'=> "Mensagem enviada!", 'msg' => $this->toArray());
    }
    
    public function atualizar($arquivos = null) {
        $temp = $this->id;

        // if ($arquivos!=null and is_uploaded_file($arquivos['foto_perfil']['tmp_name'])) {
        if ($this->estaDeclarado('foto')) {
            $this->foto_perfil = $this->mudarFoto($this->foto, $this->larguraImagem, $this->alturaImagem);
            $this->unsetAtributo('alturaImagem');
            $this->unsetAtributo('larguraImagem');
            $this->unsetAtributo('foto');
        }

        DBupdate("usuario", $this->valores_atualizar, "where id={$temp}");
        $this->valores_atualizar = array();
        
        return array('estado'=>1, 'mensagem'=>"Dados alterados com sucesso!", 'atualizado'=>$this->toArray());
    }

    public function mudarFoto($imagem, $w, $h) {
    	// var_dump($imagem); exit;
        $dirname = realpath("../..".DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'servidor'.DIRECTORY_SEPARATOR.'thumbs-usuarios'.DIRECTORY_SEPARATOR;
        $save = $this->id.time().".jpg";

        // echo pathinfo($imagem['name'], PATHINFO_EXTENSION); exit;
        
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagem));
        $img = imagecreatefromstring($data);
        imagejpeg($img, $dirname.$save, 90);
        
        if ($this->foto_perfil!=null || $this->foto_perfil!="") unlink($dirname.$this->foto_perfil);
        
        return $save;
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
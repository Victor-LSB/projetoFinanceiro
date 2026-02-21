<?php
class Transacao {
    private $valor;
    private $descricao;
    private $data;
    private $tipo;
    private $remetente;
    private $receptor;



    public function __construct($valor, $descricao, $tipo, $remetente, $receptor) {
        $this->valor = $valor;
        $this->descricao = $descricao;
        $this->tipo = $tipo;
        $this->remetente = $remetente;
        $this->receptor = $receptor;
        $this->data = date('Y-m-d H:i:s');
    }

    public function getValor(){
        return $this->valor;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function getData(){
        return $this->data;
    }
}

?>
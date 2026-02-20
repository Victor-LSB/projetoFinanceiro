<?php
class Transacao {
    private $valor;
    private $descricao;
    private $data;
    private $tipo;
    private $remetente;
    private $receptor;



    public function __construct($valor, $descricao, $data, $tipo, $remetente, $receptor) {
        $this->valor = $valor;
        $this->descricao = $descricao;
    }
}

?>
<?php

class contaCorrente {
    private $titular;
    private $saldo;
    private $tipo;
    private $limite;
    private $historico = [];


    public function __construct($titular, $saldoInicial, $tipo, $limite) {
        $this->titular = $titular;
        $this->saldo = $saldoInicial;
        $this->tipo = $tipo;
        $this->limite = $limite;
    }


    public function depositar($valor, $descricao) {
        if ($valor > 0) {
            $this->saldo += $valor;
            $this->historico[] = new Transacao($valor, $descricao, 'Depósito', null, $this->titular);
            return true;
        }
        return false;
    }

    public function sacar($valor, $descricao) {
        if ($valor > 0 && $this->saldo + $this->limite >= $valor) {
            $this->saldo -= $valor;
            $this->historico[] = new Transacao($valor, $descricao, 'Saque', $this->titular, null);
            return true;
        }
        return false;
    }

    public function exibirSaldo() {
        $limiteDisponiveil = $this->limite + $this->saldo;
        return "Saldo: R$ " . number_format($this->saldo, 2) . " | Limite Disponível: R$ " . number_format($limiteDisponiveil, 2);
    }

    public function exibirHistorico() {
        $historicoStr = "Histórico de Transações: <br>";
        foreach ($this->historico as $transacao) {
            $historicoStr .= "- " . $transacao->getTipo() . ": R$ " . number_format($transacao->getValor(), 2) . " | " . $transacao->getDescricao() . " | Data: " . $transacao->getData() . "<br>";
        }
        return $historicoStr;


    }

    public function transferir($valor, $descricao, $contaDestino) {
        if ($valor > 0 && $this->saldo + $this->limite >= $valor) {
            $this->sacar($valor, "Transferência para " . $contaDestino->titular . ": " . $descricao);
            $contaDestino->depositar($valor, "Transferência recebida: " . $descricao);
            return true;
        }
        return false;
    }

}
?>
<?php

abstract class contaCorrente {
    protected $titular;
    protected $saldo;
    protected $tipo;
    protected $limite;
    protected $historico = [];


    public function __construct($titular, $saldoInicial, $tipo, $limite) {
        $this->titular = $titular;
        $this->saldo = $saldoInicial;
        $this->tipo = $tipo;
        if ($limite < 0) {
            $this->limite = 0;
        } else {
            $this->limite = $limite;
        }
    }

    public function getTaxaTransferencia() {
        return 0;
    }

    public function depositar($valor, $descricao) {
        if ($valor > 0) {
            $this->saldo += $valor;
            $this->historico[] = new Transacao($valor, $descricao, 'Depósito', null, $this->titular);
            return true;
        }
        return false;
    }

    public function executarSaque($valor, $descricao) {
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

    public function transferir(float $valor, string $descricao, contaCorrente $contaDestino) {
        $valorComTaxa = $valor + $this->getTaxaTransferencia();
        if ($valor > 0 && $this->saldo + $this->limite >= $valorComTaxa) {
            if ($contaDestino->depositar($valor, $descricao)) {
                $this->executarSaque($valorComTaxa, "Transferência para " . $contaDestino->titular . ": " . $descricao . " (Valor bruto: R$ " . number_format($valor, 2) . ", Taxa de transferência: R$ " . number_format($this->getTaxaTransferencia(), 2) . ")");
                return true;
            }else {
                return false;  
            }
        } else {
            echo "Saldo insuficiente para transferência.";
            return false;
        }
    }

}

#Conta Poupança herda de Conta Corrente, mas tem regras específicas para juros e saques

class contaPoupanca extends contaCorrente {
    public function __construct($titular, $saldoInicial) {
        parent::__construct($titular, $saldoInicial, 'Poupança', 0);
    }

    public function getTaxaTransferencia() {
        return 2; // Conta Poupança tem taxa de transferência de R$ 2,00
    }

    public function renderJuros($taxa) {
        $juros = $this->saldo * ($taxa / 100);
        $this->depositar($juros, "Juros rendidos: " . $taxa . "%");
    }


    public function sacar($valor, $descricao) {
        $taxa = 2; // Taxa fixa de R$ 2,00 por saque
        $valorComTaxa = $valor + $taxa;
        if ($valorComTaxa > $this->saldo) {
            echo "Saldo insuficiente para saque.";
            return false;
        } else {
            $descricaoFinal = $descricao . "(Incluso taxa de saque de R$ " . number_format($taxa, 2) . ")";
            parent::executarSaque($valorComTaxa, $descricaoFinal);
        }
    }

    public function exibirExtrato() {
        $extratoStr = "Extrato da Conta Poupança: <br>";
        foreach ($this->historico as $transacao) {
            $extratoStr .= "- " . $transacao->getTipo() . ": R$ " . number_format($transacao->getValor(), 2) . " | " . $transacao->getDescricao() . " | Data: " . $transacao->getData() . "<br>";
        }
        return $extratoStr;
    }
}

class contaEmpresarial extends contaCorrente implements Transferivel {
    public function __construct($titular, $saldoInicial) {
        parent::__construct($titular, $saldoInicial, "Empresarial", 5000);
    }

    public function depositar($valor, $descricao) {
        $taxa = 5;
        $valorComTaxa = $valor - $taxa;
        if ($valorComTaxa > 0) { // Garantir que o valor após a taxa seja positivo
            parent::depositar($valorComTaxa, $descricao . " (Valor bruto: R$ " . number_format($valor, 2) . ", Taxa de depósito: R$ " . number_format($taxa, 2) . ")");
            return true;
        } else {
            echo "Valor do depósito é insuficiente para cobrir a taxa.";
            return false;
        }
    }

    public function exibirRelatorio() {
        $poderCompra = $this->saldo + $this->limite;
        $relatorioStr = "<h2>Relatório da Conta Empresarial - " . $this->titular . "</h2>";
        $relatorioStr .= "<p><strong>Saldo Atual:</strong> R$ " . number_format($this->saldo, 2) . "</p>";
        $relatorioStr .= "<p><strong>Poder de Compra:</strong> R$ " . number_format($poderCompra, 2) . "</p>";
        $relatorioStr .= "<hr>";
        $relatorioStr .= "<h3>Histórico de Transações:</h3>";
        foreach ($this->historico as $transacao) {
            $relatorioStr .= "- " . $transacao->getTipo() . ": R$ " . number_format($transacao->getValor(), 2) . " | " . $transacao->getDescricao() . " | Data: " . $transacao->getData() . "<br>";
        }
        return $relatorioStr;
    }
}

?>
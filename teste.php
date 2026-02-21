<?php
include 'classes/conta.php';
include 'classes/transacao.php';


$contaVictor = new contaPoupanca("Victor", 1000);
$contaMaria = new contaPoupanca("Maria", 0);

$contaVictor->depositar(5000, "Presente de Aniversario");
$contaVictor->sacar(1500, "Aluguel");
$contaVictor->transferir(600, "Transferência para Maria", $contaMaria);


echo "<br><br>";
echo $contaVictor->exibirSaldo();
echo "<br><br>";
echo $contaVictor->exibirHistorico();
echo "<br><br>";
echo $contaMaria->exibirSaldo();
echo "<br><br>";
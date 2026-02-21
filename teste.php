<?php
include 'classes/conta.php';
include 'classes/transacao.php';


$contaVictor = new contaCorrente("Victor", 1000, "Corrente", 500);
$contaMaria = new contaCorrente("Maria", 0, "Corrente", 0);

$contaVictor->depositar(200, "Presente de Aniversario");
$contaVictor->sacar(1500, "Aluguel");
$contaVictor->transferir(200, "Transferência para Maria", $contaMaria);



echo $contaVictor->exibirSaldo();
echo "<br><br>";
echo $contaVictor->exibirHistorico();
echo "<br><br>";
echo $contaMaria->exibirSaldo();
echo "<br><br>";
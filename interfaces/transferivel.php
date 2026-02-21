<?php
interface Transferivel {
    public function transferir (float $valor, string $descricao, contaCorrente $contaDestino);
}

?>
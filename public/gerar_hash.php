<?php
// A senha que será convertida
$senhaParaConverter = 'admin123';


$hashGerado = password_hash($senhaParaConverter, PASSWORD_DEFAULT);

echo "<h1>Hash Gerado para a senha '{$senhaParaConverter}'</h1>";
echo "<p>Copie o código abaixo e use no comando UPDATE do seu banco de dados:</p>";
echo "<hr>";
echo "<p style='font-family: monospace; font-size: 1.2em; background-color: #eee; padding: 10px; border: 1px solid #ccc;'>{$hashGerado}</p>";
<?php

function executaTudo(){
    while(true) {
        entrarSair();
    }
} 


function entrarSair ($sairEntrar){
    $sairEntrar = readline("Voce quer sair ou entrar(sair/entrar)?");
    if($sairEntrar =="entrar"){
        echo"Você logou com sucesso!";
        return true;
    }else if($sairEntrar =="sair"){
        echo "Voce saiu do projeto CAIXA!";
        return false;
    }
}

$login = ["jorge"];
$password = ["jorginho"];
$log =[];

$dataAtual = new DateTime();
$dataFormatada = $dataAtual->format('Y-m-d H:i:s');

while (true) {
    $sairEntrar = readline("Você quer entrar ou sair (entrar/sair)?");

    if ($sairEntrar === "entrar") {
        echo "Você entrou no sistema.\n";
        $digiteLogin = readline("Digite seu login: ");
        $digiteSenha = readline("Digite sua senha: ");
        $loginSucesso = false;
        
        foreach ($login as $forLogin) {
            foreach ($password as $forPassword) {
                
                if ($forPassword == $digiteSenha && $forLogin == $digiteLogin) {
                    echo "Você logou com sucesso!\n";
                    $loginSucesso = true;
                    $log[] = "As $dataFormatada o $forLogin, senha $forPassword acessou o site";
                  
                }
            }
        }

        if (!$loginSucesso) {
            echo "Senha ou login incorretos\n";
        }
    }

    if ($sairEntrar == "sair") {
        echo "Você saiu do sistema.";
        break;
    }

    if ($loginSucesso) {
        
        while (true) {
            
            $operacoes = readline("1-Cadastrar \n2-Vendas \n3-Deslogar\n");
            
            if ($operacoes == 1) {
                $cadastroLogin = readline("Digite um login para cadastro!\n");
                $cadastroSenha = readline("Digite uma senha para cadastro!\n");
                
                if ($cadastroLogin != "" && $cadastroSenha != "") {
                    $login[] = $cadastroLogin;
                    $password[] = $cadastroSenha;
                    print_r($login);
                    print_r($password);
                    print_r($log);
                
                } else {
                    echo "Você digitou senha ou login inválidos.\n";
                }
            
            }elseif($operacoes==2){
                $valorVenda = readline("Digite o valor da sua venda: \n");
                $itemVenda = readline("Digite o nome do item vendido: \n");
                $log[]="No dia $dataFormatada ocorreu a venda do item $itemVenda no valor de $valorVenda";
            } elseif ($operacoes == 3) {
                echo "Você se deslogou do sistema.\n";
                print_r($log);
                break;
            }
          
        }
    }
}

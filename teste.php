<?php

//variaveis globais
global $login;
global $password;
global $log;
global $id;
global $nomeProduto;
global $valorDosItem;
global $estoqueDosItens;
global $itensCadastrados;
//iniciliazando id
$ultimoId = 0; 
//arrays
$itensCadastrados = []; 
$login = ["jorge"];
$password = ["jorginho"];
$log = [];
$id = [];
$nomeProduto = [];
$valorDosItem = [];
$estoqueDosItens = [];
//data
global $dataAtual;
global $dataFormatada;

$dataAtual = new DateTime();
$dataFormatada = $dataAtual->format('Y-m-d H:i:s');

function executaTudo(){
    global $loginSucesso;
    
    while(true) {
        $sairEntrar = readline("Você quer sair ou entrar (sair/entrar)? ");
        if($sairEntrar === "entrar"){
            do {
                $digiteLogin = readline("Digite seu login: ");
                $digiteSenha = readline("Digite sua senha: ");
                confereLogin($digiteLogin, $digiteSenha); 
            } while (!$loginSucesso);
            escolhas();
        } elseif($sairEntrar === "sair"){
            echo "Você saiu do projeto CAIXA!";
            break;
        }
    }
} 
function vendas($dinheiroEmCaixa){
    global $dataFormatada, $log, $itensCadastrados;

    $idItem = readline("Digite o id do item que você quer vender: \n");

    if (isset($itensCadastrados[$idItem])) {
        $item = $itensCadastrados[$idItem];

        echo "Item selecionado: {$item['nomeProduto']}, Valor: {$item['valorDosItem']}\n";

        $quantidade = readline("Digite a quantidade que você quer comprar: \n");

        if ($quantidade <= $item["estoqueDosItens"]) {
            $valorPreCompra = $quantidade * $item["valorDosItem"];

            $pagamento = readline("Digite o valor de pagamento: \n");

            if ($pagamento < $valorPreCompra) {
                echo "O valor pago pelo cliente é insuficiente para concluir a compra!\n";
                return;
            }

            $troco = $pagamento - $valorPreCompra;

            if ($troco > $dinheiroEmCaixa) {
                echo "Você não tem dinheiro em caixa suficiente para dar o troco, a venda foi cancelada.\n";
                $log[] = "No dia $dataFormatada não pôde ocorrer a venda do item {$item['nomeProduto']} no valor de $valorPreCompra por falta de dinheiro em caixa";
                return; 
            }

            $itensCadastrados[$idItem]["estoqueDosItens"] -= $quantidade;

            echo "Troco: $troco. Venda concluída com sucesso.\n";
            $log[] = "No dia $dataFormatada ocorreu a venda do item {$item['nomeProduto']} no valor de $valorPreCompra";
        } else {
            echo "Quantidade indisponível em estoque para o item selecionado.\n";
        }
    } else {
        echo "Item não encontrado.\n";
    }
}


function cadastro (){

    global $login, $password, $loginSucesso, $log, $dataFormatada;

    $cadastroLogin = readline("Digite um login para cadastro!\n");
    $cadastroSenha = readline("Digite uma senha para cadastro!\n");
    
    if ($cadastroLogin != "" && $cadastroSenha != "") {
        $login[] = $cadastroLogin;
        $password[] = $cadastroSenha;
        
        $log[]="No dia $dataFormatada ocorreu o cadastro do login $cadastroLogin, usando a senha $cadastroSenha";
        
        $arquivo = fopen("teste.txt", "a");
            fwrite($arquivo, "No dia $dataFormatada ocorreu o cadastro do login $cadastroLogin, usando a senha $cadastroSenha\n");      
            fclose($arquivo);
        
            print_r($login);
        print_r($password);
        
    
    } else {
        echo "Você digitou senha ou login inválidos.\n";
    }
}

function escolhas(){
    global $login, $password, $loginSucesso, $log, $dataFormatada;
   
    $dinheiroEmCaixa = readline("Digite o valor que você tem em caixa: \n");
    
    while (true) {
        $operacoes = readline("1-Cadastrar \n2-Vendas \n3-Cadastro de itens\n4-deslogar");

        switch ($operacoes) {
            
            case 1:
                cadastro();
                break;
            
            case 2:
                vendas($dinheiroEmCaixa);
                break;
            
            case 3:
                cadastroItens();
                break;
            
            case 4:
                echo "Você se deslogou do sistema.\n";
                print_r($log);
                print_r($login);
                print_r($password);
               
                return; 
            
            default:
                echo "Opção inválida. Por favor, selecione uma das opções válidas (1, 2, 3 ou 4).\n";
        }
    }
}


function confereLogin($digiteLogin, $digiteSenha){
    global $login, $password, $loginSucesso, $log, $dataFormatada;
    
    $loginSucesso = false; 
    
    foreach ($login as $key => $forLogin) {
        if ($password[$key] == $digiteSenha && $forLogin == $digiteLogin) {
            echo "Você logou com sucesso!\n";
            $loginSucesso = true;
            $log[] = "Em $dataFormatada, o usuário $forLogin acessou o sistema.";
            
            $arquivo = fopen("teste.txt", "a");
            fwrite($arquivo, "Em $dataFormatada, o usuário $forLogin acessou o sistema.\n");
            
            fclose($arquivo);
            return; 
        }
    }

    if (!$loginSucesso) {
        echo "Senha ou login incorretos\n";
    }
}

function cadastroItens (){
    global $ultimoId;
    
    $nomeItem = readline("Digite o nome do item que você quer cadastrar: \n") ;
    $valorItem = readline("Digite o valor do Item: \n");
    $estoqueItem = readline("Digite a quantidade de estoque do item: \n");   
    cadastrarItem($nomeItem, $valorItem, $estoqueItem);  
}

function cadastrarItem($nomeItem, $valorItem, $estoqueItem){
    global $ultimoId, $itensCadastrados;
    
    $novoId = ++$ultimoId; 
    
   
    $itensCadastrados[$novoId] = [
        "nomeProduto" => $nomeItem,
        "valorDosItem" => $valorItem,
        "estoqueDosItens" => $estoqueItem
    ];
    
    
    echo "Item cadastrado com ID: $novoId, Nome: $nomeItem, Valor: $valorItem, Estoque: $estoqueItem.\n";
}


    executaTudo();



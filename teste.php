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
global $valorTotal;
//iniciliazando id
$ultimoId = 0; 
//arrays
$valorTotal = [];
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
    global $dataFormatada, $log, $itensCadastrados, $valorPreCompra,$valorTotal;

    $idItem = readline("Digite o id do item que você quer vender: \n");

    if (isset($itensCadastrados[$idItem])) {
        $item = $itensCadastrados[$idItem];

        echo "Item selecionado: {$item['nomeProduto']}, Valor: {$item['valorDosItem']}, Estoque: {$item['estoqueDosItens']}\n";

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

            $valorTotal[]=$valorPreCompra;
            echo "Troco: $troco. Venda concluída com sucesso.\n";
            $log[] = "No dia $dataFormatada ocorreu a venda do item {$item['nomeProduto']} no valor de $valorPreCompra";
            
            $arquivo = fopen("teste.txt", "a");
            fwrite($arquivo, "No dia $dataFormatada ocorreu a venda do item {$item['nomeProduto']} no valor de $valorPreCompra.\n");      
            fclose($arquivo);
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
        $operacoes = readline("\n 1-Cadastrar \n 2-Vendas \n 3-Cadastro de itens \n 4-Log do sistema \n 5-Remove Item \n 6-Editar Item \n 7-Valor total de vendas. \n 8-Deslogar");

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
                print_r($log);
                break;  
            
            case 5:
                removeItem();    
                break;        
            
            case 6:
                editaItem();    
                break;      

            case 7:
                valorTotal();
                break;

            case 8:
                echo "Você se deslogou do sistema.\n";
                print_r($log);
                print_r($login);
                print_r($password);
               
                return; 
            
            default:
                echo "Opção inválida. Por favor, selecione uma das opções válidas (1, 2, 3, 4, 5, 6, 7 ou 8).\n";
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
        $arquivo = fopen("teste.txt", "a");
        fwrite($arquivo, "No dia $dataFormatada ocorreu uma tentativa de login falha.\n");      
        fclose($arquivo);
    }
}

function cadastroItens (){
    global $dataFormatada, $novoId;
    
    $nomeItem = readline("Digite o nome do item que você quer cadastrar: \n") ;
    $valorItem = readline("Digite o valor do Item: \n");
    $estoqueItem = readline("Digite a quantidade de estoque do item: \n");   
    cadastrarItem($nomeItem, $valorItem, $estoqueItem);  

    $arquivo = fopen("teste.txt", "a");
    fwrite($arquivo, "No dia $dataFormatada ocorreu o cadastro do produto de id de $novoId,produto chamado $nomeItem, no valor de $valorItem, com a quantidade de $estoqueItem.\n");      
    fclose($arquivo);


}

function cadastrarItem($nomeItem, $valorItem, $estoqueItem){
    global $ultimoId, $itensCadastrados, $novoId;
    
    $novoId = ++$ultimoId; 
    
   
    $itensCadastrados[$novoId] = [
        "nomeProduto" => $nomeItem,
        "valorDosItem" => $valorItem,
        "estoqueDosItens" => $estoqueItem
    ];
    
    
    echo "Item cadastrado com ID: $novoId, Nome: $nomeItem, Valor: $valorItem, Estoque: $estoqueItem.\n";
}


function removeItem(){
    global $itensCadastrados,$dataFormatada,$log;

    $idRemove = readline("Digite o numero do id que voce deseja remover:");
    if(isset($itensCadastrados[$idRemove])){
        unset($itensCadastrados[$idRemove]);
        echo"O item $idRemove foi removido com sucesso. \n";
        
        $log[]="No dia $dataFormatada ocorreu a remocao do ID $idRemove.\n";      
        

        $arquivo = fopen("teste.txt", "a");
        fwrite($arquivo, "No dia $dataFormatada ocorreu a remocao do ID $idRemove.\n");      
        fclose($arquivo);
        
       

    }else{
        echo "O item $idRemove nao foi encontrado.\n";
    }
}

    function editaItem(){
        global $itensCadastrados,$dataFormatada,$log;
    
        $idEdita = readline("Digite o numero do id que voce deseja editar: \n");
        if(isset($itensCadastrados[$idEdita])){
            $escolhaEditar = readline("voce deseja alterar valor ou estoque? \n");
            if($escolhaEditar === "estoque"){
                
                $novoEstoque = readline("Digite o novo estoque do produto.");
                $itensCadastrados[$idEdita]["estoqueDosItens"] = $novoEstoque;

                $log[]="No dia $dataFormatada ocorreu a mudanca de estoque para $novoEstoque.\n"; 

                $arquivo = fopen("teste.txt", "a");
        fwrite($arquivo, "No dia $dataFormatada ocorreu a mudanca de estoque para $novoEstoque.\n");      
        fclose($arquivo);

        echo"Voce alterou o valor do $idEdita para $novoEstoque.";

            }else if($escolhaEditar === "valor"){

                $novoValor = readline("Digite o novo valor do produto.");
                $itensCadastrados[$idEdita]["valorDosItem"] = $novoValor;
             

                $log[] ="No dia $dataFormatada ocorreu a mudanca de estoque para $novoValor.\n";  
                $arquivo = fopen("teste.txt", "a");
        fwrite($arquivo, "No dia $dataFormatada ocorreu a mudanca de estoque para $novoValor.\n");      
        fclose($arquivo);

        echo"Voce alterou o valor do $idEdita para $novoValor.";

            }else{
                echo "Escolha invalida. \n";
            }

        }
    }

    function valorTotal (){
        global $valorTotal,$log;
        $total =0;

        foreach($valorTotal as $valor){
            $total += $valor;
        }

        echo"O valor final de todas as vendas eh $total.\n";
        $log[] ="O valor final de todas as vendas eh $total.\n";  
                $arquivo = fopen("teste.txt", "a");
        fwrite($arquivo, "O valor final de todas as vendas eh $total.\n");      
        fclose($arquivo);
    
    }

    executaTudo();



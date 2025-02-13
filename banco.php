Trabalho feito por Victor Jesus da Silveira e Eduardo Gonella

<?php

//Globais

$clientes = [];
$contas = [];
$valida = 0;

//menu

function menuCliente($numeroConta)
{

    global $contas;
    global $clientes;

    print("1 - Sacar\n");
    print("2 - Verificar saldo\n");
    print("3 - Depositar\n");
    print("4 - Criar nova conta\n");
    print("5 - Sair\n");


    $opcao = readline("Qual operacao deseja: ");

    if ($opcao == 1) {
        apaga();
        do {
            $quantia = readline("Informe o valor que deseja sacar: ");
            if ($quantia <= 0) {
                print("Valor inexistente ou igual a zero. Informe novamente.\n");
            }
        } while ($quantia <= 0);
        sacar($contas, $numeroConta, $quantia);
        menuCliente($numeroConta);
    } elseif ($opcao == 2) {
        apaga();
        consultarSaldo($contas, $numeroConta);
        menuCliente($numeroConta);
    } elseif ($opcao == 3) {
        apaga();
        do {
            $quantia = readline("Informe o valor que deseja depositar: ");
            if ($quantia <= 0) {
                print("Valor inexistente ou igual a zero. Informe novamente.\n");
            }
        } while ($quantia <= 0);


        depositar($contas, $numeroConta, $quantia);
        menuCliente($numeroConta);
    } else if ($opcao == 4) {
        apaga();
        CriarConta();
    } else if ($opcao == 5) {
        exit("Obrigado por usar nosso banco!\n");
    } else {
        echo "Opção inválida!\n";
        menuCliente($numeroConta);
    }
}

//criar conta

function CriarConta()
{

    global $valida;
    global $contas;
    global $clientes;

    //Cadastro
    echo ("Bem vindo ao banco TDS!\n");
    echo ("Para começar, você precisa se cadastrar como cliente.\n");

    do {
        do {
            $cpf = readline("Informe seu cpf: ");
            if (strlen($cpf) != 11) {
                print("CPF inválido. Informe novamente.\n");
            }
        } while (strlen($cpf) != 11);
        validar_cpf($cpf);
    } while ($valida == 0);

    $nome = readline("Informe seu nome: ");
    do {
        $telefone = readline("Informe seu número de telefone (Exemplo: 991362321): ");
        if (strlen($telefone) != 9) {
            print("Número de telefone invalido, tente novamente.\n");
        }
    } while (strlen($telefone) != 9);
    cadastrarCliente($clientes, $nome, $cpf, $telefone);
    echo ("Cadastro feito! Agora vamos cadastrar uma conta.\n");

    //Conta
    do {
        do {
            $cpfCliente = readline("Informe seu cpf: ");
            if (strlen($cpfCliente) != 11) {
                print("CPF inválido. Informe novamente.\n");
            }
        } while (strlen($cpfCliente) != 11);
        validar_cpf($cpfCliente);
    } while ($valida == 0);

    $numeroConta = cadastrarConta($contas, $cpfCliente);



    menuCliente($numeroConta);
}

//cadastrar cliente

function cadastrarCliente(&$clientes, string $nome, string $cpf, string $telefone): void
{

    //Global $clientes; Alternativa para acesso de variável

    $cliente = [
        "nome" => $nome,
        "cpf" => $cpf, //11 digitos
        "telefone" => $telefone, //10 digitos
    ];


    $clientes[] = $cliente;
}

//cadastrar conta

function cadastrarConta(&$contas, $cpfCliente)
{

    global $clientes;

    foreach ($clientes as $c) {

        if ($c['cpf'] == $cpfCliente) {
            $conta = [
                "numeroConta" => uniqid(),
                "cpfCliente" => $cpfCliente,
                "saldo" => 0
            ];

            $contas[] = $conta;

            return $conta['numeroConta'];
        }
    }

    // echo("CPF não cadastrado, digite novamente.");


    print_r($contas);

}

//depositar

function depositar(&$contas, $numero_conta, $valor)
{
    foreach ($contas as &$conta) {
        if ($conta['numeroConta'] == $numero_conta) {
            if ($valor > 0) {
                $conta['saldo'] += $valor;
                echo "Depósito de R\${$valor} realizado com sucesso na conta $numero_conta\n";
            } else {
                echo "Depósito inválido, valor igual a 0 ou negativo. Tente de novo\n";
            }
        } else {
            echo "Conta $numero_conta não encontrada.";
        }
    }
}

//Sacar

function sacar(&$contas, $numero_conta, $valor)
{
    foreach ($contas as &$conta) {
        if ($conta['numeroConta'] == $numero_conta) {
            $conta['saldo'] = $conta['saldo'] - $valor;
            print "O valor de R\$$valor foi retirado do seu saldo.\n";
        } else {
            print "Conta $numero_conta não encontrada.";
        }
    }
}

//consultar saldo

function consultarSaldo(&$contas, $numero_conta)
{
    foreach ($contas as &$conta) {
        if ($conta['numeroConta'] == $numero_conta) {
            print "Saldo da conta [$numero_conta]: R\${$conta['saldo']}\n";
        }
    }
}

//confirma cpf

function validar_cpf($cpf)
{

    global $valida;

    $valida = 0;

    //soma1

    $soma = 0;

    for ($i = 0; $i < 9; $i++) {
        $soma += $cpf[$i] * (10 - $i);
    }


    $valor = (int) ($soma / 11);
    $resto = $soma % 11;

    if ($resto < 2) {
        $digito1 = 0;
    } else if ($resto >= 2) {
        $digito1 = (11 - $resto);
    }

    //soma2

    $soma2 = 0;

    for ($i = 0; $i < 9; $i++) {
        $soma2 += ($cpf[$i] * (11 - $i));
    }

    $soma2 += ($digito1 * 2);

    $valor2 = (int) ($soma2 / 11);
    $resto2 = $soma2 % 11;

    if ($resto2 < 2) {
        $digito2 = 0;
    } else if ($resto2 >= 2) {
        $digito2 = (11 - $resto2);
    }


    //validação



    if ($digito1 != $cpf[9]) {
        $validar_cpf = false;
    } else {
        if ($digito2 == $cpf[10]) {
            $validar_cpf = true;
        } else {
            $validar_cpf = false;
        }
    }

    if ($validar_cpf) {
        $valida = 1;
    } else {
        echo ("Seu CPF: $cpf não é valido, informe-o novamente.\n");
        $valida = 0;
    }
}

//apaga

function apaga()
{
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
    echo ("\n");
}

//inicio

CriarConta();

?>

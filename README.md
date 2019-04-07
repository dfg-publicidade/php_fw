# DFG_FW
DFG Framework for PHP

[![DFG Studio](http://dfgstudio.com.br/res/images/dfgstudio.png)](https://dfgstudio.com.br)

Apresentamos o Framework DFG para desenvolvimento ágil em PHP. Os principais objetivos desse framework são:

  - Desenvolvimento simplificado no padrão MVC
  - Roteamento facilitado dos recursos
  - Utilização simplificada do framework Doctrine no acesso a dados

O framework utiliza a licença [LGPL v2.1][lgpl]. Seu código-fonte é aberto e disponível no [GitHub][github] sendo de livre utilização e modificação. Sugestões são sempre bem vindas :) 

## Versões

### v1.9 [atual]

Adição de métodos de transformação de strings em date e datetime

### v1.8

Adição da classe ImageResize para redimensionamento de imagens

### v1.7

Adição de Symfony Serializer nas dependências

### v1.6

Escolha do template no envio dos e-mails

### v1.5

Suporte a operações RestFul com configuração via routing.php

### v1.4

Adição da opção para envio de anexos no utilitário de e-mails

### v1.3

Adição da função YEAR (MySQL)

### v1.2

Atualizações na classe Util

### v1.1

Carregamento de entidades e serviços externos

### v1.0

Primeira versão estável do framework

## Instalação

DFG_FW está disponível no [Composer/Packgist][packgist], bastando adicionar a seguinte instrução ao arquivo `composer.json` de seu projeto:

```json
"dfgstudio/dfg_fw": "1.*"
```

ou no terminal

```sh
composer require dfgstudio/dfg_fw
```

A estrutura de arquivos deve ser a seguinte:

- Raiz do projeto
  - [D] app
    - **[D] controllers:** diretório dos controladores (controllers MVC)
    - **[D] models:** entidades do banco de dados (models MVC)
    - **[D] services:** classes de serviço das entidades (acesso e gerenciamento dos dados por meio das entidades)
    - **[D] templates:** *templates* das páginas
    - **[D] views:** páginas para exibição do conteúdo gerado (views MVC)
  - [D] config
    - **[F] config.php:** configuraçõo do projeto
    - **[F] routing.php:** roteamento do projeto
  - **[D] resources:** arquivos gerais (arquivos .txt, *templates* para envio de e-mails, etc.)
  - **[D] tmp\*:** arquivos temporários
    - **[D] cache\*:** arquivos de cache
  - **[D] webapp:**  arquivos web (acesso livre)

[D] Diretórios

[F] Arquivos

\* Diretórios criados automaticamente

Em seguida, deve ser criado o arquivo `.htaccess` na raiz do projeto como:

```xml
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteRule     ^$    webapp/     [L]
    RewriteRule     (.*)/$ webapp/$1  [L]
    RewriteRule     (.*) webapp/$1    [L]
</IfModule>
```

*Caso necessite, adições podem ser feitas no `.htaccess`, desde que se mantenha o redirecionamento básico informado acima.*

Também deve ser criado o arquivo `.htaccess` em  *webapp*:

```xml
<IfModule mod_rewrite.c>
    RewriteEngine   On
    RewriteCond     %{REQUEST_FILENAME} !-f
    RewriteCond     %{REQUEST_FILENAME} !-d

    RewriteRule     ^(.*)$  index.php?url=$1    [PT,L,QSA]

    ErrorDocument 400 error.html?err=404
    ErrorDocument 401 error.html?err=401
    ErrorDocument 403 error.html?err=403
    ErrorDocument 404 error.html?err=404
    ErrorDocument 500 error.html?err=500
    ErrorDocument 503 error.html?err=503
</IfModule>
```

Como pode ser visto, a regra acima faz o redirecionamento para dois arquivos: `webapp/index.php` e `webapp/error.php`. O primeiro contém a instrução inicial do framework, que deve ser:

```php
<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

require_once(ROOT . DS . 'vendor' . DS . 'dfgstudio' . DS . 'dfg_fw' . DS . 'src' .  DS . 'init.php');
```

Já o segundo arquivo contém a página de erro do projeto, podendo ser criado livremente utilizando o parâmetro enviado `err` para identificar o erro encontrado. 

O arquivo `config/config.php` contém as configurações do projeto, conforme o exemplo:

```php
<?php

//Ambiente de desenvolvimento (ativa ou não a exibição de erros)
define('DEVELOPMENT_ENVIRONMENT', true);

//Timezone
date_default_timezone_set("America/Sao_Paulo");

//Configurações do banco de dados
define('DB_DRIVER', 'pdo_mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'meudb');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_CHARSET', 'latin1');
define('DB_OPTIONS', serialize(array(
    1002 => 'SET NAMES utf8'
)));

//É possível definir a utilização de entidades e serviços externos por meio das seguintes constantes
define('ENTITY_DIR', ROOT . DS . '../<MINHA BIBLIOTECA>/app/models/');
define('SERVICE_DIR', ROOT . DS . '../<MINHA BIBLIOTECA>/app/services/');

//Configurações do serviço de e-mail
define('EMAIL_HOST', 'meuservidorsmtp.com.br');
define('EMAIL_PORT', '587');
define('EMAIL_USER', 'meuemail@teste.com.br');
define('EMAIL_PASSWD', 'minhasenha');
define('EMAIL_NOREPLY', 'meuemail@teste.com.br');

//Chave de criptografia (16 caracteres)
define('SECURITY_KEY', '1234567890ABCDFG');

//Context path
define('CONTEXT_PATH', 'http://meuprojeto.com.br/');

//Paginação
define('PAGINATE_LIMIT', '20');
define('PAGINATE_MAX_LNKS', '5');

//Header charset
header('Content-Type: text/html; charset=UTF-8');

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
```

Feitas as configurações, é necessário definir as regras de roteamento no arquivo `config/routing.php`:

```php
<?php

$routing = array(
    /**
     ** Padrão das regras: 
     ** '<REGRA>' => '<CONTROLADOR>/<ACAO>/<PARAMETRO 1>/<PARAMETRO 2>/<PARAMETRO N>'
     ** ou
     ** '[<METODO>, <REGRA>]' => '<CONTROLADOR>/<ACAO>/<PARAMETRO 1>/<PARAMETRO 2>/<PARAMETRO N>'
     ** - Os parâmetros são escritos utilizando PHP regex
     ** - O nome do controlador corresponde ao nome da classe com o primeiro caractere minúsculo e sem o sufixo "Controller"
     ** - O nome da ação corresponde ao nome do método do controlador com o primeiro caractere minúsculo
     **/

    //HOME
    '/^$/' => 'home/main', //Corresponde a URL "<ENDEREÇO DO SITE'>/"
    '/^home$/' => 'home/main', //Corresponde a URL "<ENDEREÇO DO SITE'>/home"
    
    //Exemplo de listagem
    '/^produtos$/' =>           ['GET' => 'produto/main'], //Corresponde a URL "<ENDEREÇO DO SITE'>/produtos"
    '/^produtos\/([0-9]+)$/' => ['GET' => 'produto/main/\1''] //Corresponde a URL "<ENDEREÇO DO SITE'>/produtos/2", onde 2 é o número da página atual
);

$default['controller'] = 'home'; //controller principal
$default['action'] = 'main'; //ação principal
```

**Obs:** Requisições que não informem nenhum controlador serão direcionadas ao controlador principal. Do mesmo modo, quando não for informada ação, será chamada a ação principal.

Feito o redirecionamento, deve ser criado um controlador, extendendo a classe DefaultController do DFG_FW:

```php
<?php

/**
 ** Controller de gerenciamento da página principal
 ** @author DFG Studio
 **/
class HomeController extends DefaultController {
    //Define o arquivo de template utilizado. No exemplo: templates/index.php
    //Se o valor de $template_file for vazio, nenhum template será utilizado
    protected $template_file = 'index';

    /**
     ** O método main é automaticamente redirecionado para views/home/main.php, seguindo o padrão views/<CONTROLADOR>/<AÇÃO>
     **/
    function main() {
        //O redirecionamento pode ser evitado alterando a flag de renderização para 0 (o padrão é 1)
        $this->render = 0;
    
        //Abaixo estão exemplos de métodos dos controladores
        
        //Obtém o Doctrine Entity Manager
        $em = $this->getEntityManager();
        
        //Obtém a flag de renderização
        $render = $this->getRender();
        
        //Envia uma variável para a view
        $this->set('variavel', $valor);
        
        //Obtém o valor de uma variável
        $valor = $this->get('variavel');
        
        //Obtém o valor de um parâmetro (GET ou POST)
        $param = $this->getParameter('parametro');
        
        //Altera a ação atual (somente a página de destino)
        $this->redirect($action);
        
        //Altera a ação atual (chamando nova execução do método no controlador)
        $this->redirectAction($action);
        
        //Altera momentaneamente o arquivo de template
        $this->setTemplate($template_file);
        
        //Altera momentaneamente a flag de renderização
        $this->setRender($render);
    }
}
```

Um exemplo do template `templates/index.php` pode ser dado por:

```html
<!DOCTYPE html>
<html>
    <head>
        <title>DFG Studio</title>
        <?
            //Inclui o arquivo compartilhado do template templates/shared/head.php
            $template->requiref('shared' . DS . 'head.php');
        ?>
    </head>
    <body>
        <?
            //Renderiza o código gerado pelo controlador e pela página correspondentes
            $template->render();
        ?>
    </body>
    <?
        //Inclui o arquivo compartilhado do template templates/shared/js.php
        $template->requiref('shared' . DS . 'js.php')
    ?>
    <?
        //Imprime o código javascript coletado nas páginas a fim de organizá-lo no fim da página
        $html->printCollectedJs();
    ?>
</html>
```

Um exemplo da página `views/home/index.php` poderia ser:

```html
<?
    //Atalho para <link rel="stylesheet" type="text/css" href="<CONTEXT_PATH>res/css/meucss.css" media="screen" />
    $html->includeCss('meucss', 'screen');
?>

<main>
    <div class="container">
        Meu conteúdo
    </div>
</main>    

<?
    //Inicia a coleta do javascript para organização
    $html->startCollectJs();
    
    //Atalho para <script src="<CONTEXT_PATH>/res/js/meujs.js"></script>
    $html->includeJs('meujs');
    
    //Finaliza a coleta do javascript para organização
    $html->stopCollectJs();
?>
```

Mais informações sobre os métodos podem ser obtidos na documentação que acompanha o projeto.

## Atualizações previstas

 - Métodos nativos de autenticação (HTTP Basic / Via banco de dados)
 - Métodos nativos de validação
 - Envio nativo de mensagens
 - Controle nativo de "Access-Control-Allow-Origin"
 - Métodos nativos de impressão para JSON e imagens
 - Testes com PHPUnit

## Como contribuir com o projeto?

Por meio de doações [clicando aqui][doar] ou sendo um revisor/editor do projeto (entre em contato para integrar nosso time de editores).

## Sobre nós e Contato

Web: http://dfgstudio.com.br

Facebook: https://www.facebook.com/dfgstudio

Mande-nos seu feedback: talk@dfgstudio.com.br

## Licença
[GNU Lesser General Public License, version 2.1][lgpl]

   [lgpl]: <https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html>
   [github]: <https://github.com/gabrielduarte88/dfg_fw/docs/index.html>
   [packgist]: <https://packagist.org/packages/dfgstudio/dfg_fw>
   [doar]: <https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MMGH46DWYFFL2>

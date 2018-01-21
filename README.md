# Instalador do Painless

O Painless/ Installer é o instalador do Painless.

##### Atenção
O Painless utiliza o Composer para gerenciar suas dependências. Então, antes de usar o Painless, certifique-se que o Composer esteja instalado em seu computador.

##### Instalando via Composer

Primeiro, baixe o instalador do Painless usando o Composer:

> _composer global require painless/installer_

##### Adicionando ao $PATH
Insira o caminho do arquivo binário do Painless no Path do seu sistema operacional, este arquivo deverá estar na pasta global do Composer. Geralmente fica em:

##### Windows
> _%APPDATA%\Composer\vendor\bin_

##### MacOS
> _$HOME/.composer/vendor/bin_

##### GNU/ Linux Distributions
> _$HOME/.composer/vendor/bin_

Após instalado um novo comando chamado "*painless*" será habilitado do seu terminal e este será capaz de criar uma nova instalação do Painless no diretório de sua escolha. Por exemplo, o comando:

> "_painless site_"

Cria um diretório chamado "*site*" contendo todos os arquivos necessários para o funcionamento do Painless.
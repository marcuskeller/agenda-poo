# Agenda Telefônica

Aplicação web em PHP para cadastro e organização de contatos. O projeto usa uma estrutura orientada a objetos com controllers, serviços, repositórios, entidades e views, e persiste os dados em MySQL.

## Funcionalidades

- Cadastro e autenticação de usuários.
- Cadastro, listagem, edição e exclusão de contatos.
- Contatos isolados por usuário; telefone e e-mail não se repetem dentro da agenda do mesmo usuário.
- Área administrativa para listar, editar e ativar ou inativar usuários.
- Validação de nomes, e-mails, senhas e telefones.

## Requisitos

- PHP 8.1 ou superior, com as extensões `mysqli` e `mysqlnd`.
- Docker Desktop com Docker Compose.

## Como executar no Windows

Execute os comandos a partir da pasta raiz do projeto.

1. Inicie o MySQL:

   ```powershell
   docker compose up -d mysql
   ```

   O serviço usa MySQL 8.4 e fica disponível em `127.0.0.1:3309`.

2. Crie o banco e as tabelas:

   ```powershell
   cmd /c "docker exec -i agenda-poo mysql -uroot -proot < app\Infrastructure\agenda-database.sql"
   ```

   **Atenção:** o script remove o banco `agenda` se ele já existir e o cria novamente. Isso apaga os dados anteriores.

3. Inicie o servidor PHP embutido:

   ```powershell
   php -S 127.0.0.1:8000 -t app
   ```

4. Acesse <http://127.0.0.1:8000/Controller/Auth/AuthController.php?action=login>.

Para encerrar o MySQL:

```powershell
docker compose down
```

## Acesso inicial

O script de criação do banco insere um usuário administrador:

- **E-mail:** `admin@agenda.com`
- **Senha:** `admin123`

Contas novas criadas pela tela de cadastro recebem o perfil de usuário comum. O administrador pode gerenciar usuários; usuários comuns acessam sua própria agenda.

## Configuração do banco

A conexão está definida diretamente em `app/Infrastructure/ConfigDatabase.php`:

| Configuração | Valor |
| --- | --- |
| Host | `127.0.0.1` |
| Porta | `3309` |
| Banco | `agenda` |
| Usuário | `root` |
| Senha | `root` |

Esses valores correspondem ao serviço definido em `docker-compose.yml`. Se alterar a configuração do serviço, atualize também os valores em `ConfigDatabase.php`.

## Estrutura do projeto

```text
app/
├── Controller/       # Fluxo de autenticação, usuários e contatos
├── Infrastructure/   # Conexão, autenticação de sessão e scripts SQL
├── Models/           # Entidades, serviços e repositórios
├── Views/            # Telas PHP e estilos
└── docs/             # Material de apoio do projeto
docker-compose.yml    # Serviço MySQL
```

## Observações de segurança

Este projeto é voltado a estudo e desenvolvimento local. As credenciais do banco estão no código, e o sistema usa MD5 para armazenar senhas; ambos devem ser substituídos por soluções apropriadas antes de qualquer implantação em produção. Troque também a senha do administrador padrão e não execute o script SQL de inicialização em um banco com dados que deseja preservar.

## Testes

O repositório não contém uma suíte automatizada de testes configurada.

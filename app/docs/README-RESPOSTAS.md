# Comparação entre as versões procedural e orientada a objetos

As respostas comparam a versão procedural da pasta `estruturada`, consultada separadamente, com a versão orientada a objetos deste repositório.

## 1. Qual é a principal diferença entre a versão procedural e a versão orientada a objetos?

Na versão procedural, o fluxo é organizado principalmente em arquivos PHP para cada operação. Esses arquivos executam instruções, manipulam dados e, em alguns casos, também exibem HTML. Na versão orientada a objetos, os dados e comportamentos são organizados em classes com responsabilidades distintas, separadas em camadas.

Por exemplo, o `create.php` procedural valida o formulário e faz a inserção no banco no mesmo arquivo. Na versão POO, o fluxo passa pelo [ContactController](app/Controller/Contacts/ContactController.php), pelo [ContactService](app/Models/Contacts/ContactService.php), pela [ContactEntity](app/Models/Contacts/ContactEntity.php) e pelo [ContactRepository](app/Models/Contacts/ContactRepository.php).

Além da reorganização, a versão POO acrescenta funcionalidades como autenticação, usuários e agendas individuais. Portanto, as versões não diferem apenas no paradigma utilizado.

## 2. Quais responsabilidades estavam concentradas nos arquivos da versão procedural?

Os arquivos da versão procedural concentravam várias tarefas:

- **Receber e processar requisições:** ler dados enviados por `GET` ou `POST`.
- **Validar entradas:** conferir campos obrigatórios e, no cadastro, o formato do e-mail.
- **Acessar o banco:** preparar e executar consultas SQL.
- **Controlar o fluxo:** tratar erros e redirecionar após operações.
- **Exibir a interface:** gerar tabelas, formulários e mensagens em HTML.

Por exemplo, `create.php` reúne a validação, o `INSERT` e o formulário; `index.php` consulta e exibe os contatos. A conexão, por sua vez, já estava separada em `config/database.php`, sendo incluída pelos demais arquivos.

## 3. Como essas responsabilidades foram distribuídas na versão POO?

- **Controllers:** recebem as requisições, escolhem a ação, chamam os serviços e encaminham para views ou redirecionamentos.
- **Entidades:** representam contatos e usuários, armazenam seus dados e oferecem operações de preenchimento e validação.
- **Serviços:** coordenam regras de negócio, validações e operações entre entidades e repositórios.
- **Repositórios:** executam as consultas e alterações no banco de dados.
- **Views:** exibem formulários, listas e mensagens.
- **Infraestrutura:** concentra a conexão com o banco e o gerenciamento de sessão e acesso.

Essa divisão não é totalmente rígida: por exemplo, os controllers ainda leem diretamente `$_POST` e as views contêm código PHP para apresentar os dados.

## 4. Quais classes foram criadas e qual é a responsabilidade de cada uma?

| Classe | Responsabilidade |
| --- | --- |
| [BaseController](app/Controller/BaseController.php) | Reunir a conexão com o banco e operações HTTP comuns aos controllers. |
| [ContactController](app/Controller/Contacts/ContactController.php) | Controlar as ações de listagem, criação, edição e exclusão de contatos. |
| [AuthController](app/Controller/Auth/AuthController.php) | Controlar cadastro, login e logout. |
| [UserController](app/Controller/Users/UserController.php) | Controlar a listagem, edição e ativação ou inativação de usuários pelo administrador. |
| [ContactEntity](app/Models/Contacts/ContactEntity.php) | Representar os dados de um contato e validar seus campos. |
| [ContactService](app/Models/Contacts/ContactService.php) | Coordenar as operações e regras dos contatos, incluindo validação e verificação de duplicidade. |
| [ContactRepository](app/Models/Contacts/ContactRepository.php) | Consultar e persistir contatos no banco de dados. |
| [UserEntity](app/Models/Users/UserEntity.php) | Representar os dados de um usuário e validar seus campos. |
| [UserService](app/Models/Users/UserService.php) | Coordenar as operações de usuários, incluindo cadastro, autenticação e validações. |
| [UserRepository](app/Models/Users/UserRepository.php) | Consultar e persistir usuários no banco de dados. |
| [Auth](app/Infrastructure/Auth.php) | Gerenciar a sessão e verificar o perfil necessário para acessar determinadas áreas. |

O arquivo [ConfigDatabase.php](app/Infrastructure/ConfigDatabase.php) configura a conexão, mas não define uma classe. As interfaces dos repositórios são descritas na resposta 8.

## 5. Onde foram utilizados atributos e métodos?

Os **atributos** armazenam o estado das classes. Na `ContactEntity`, por exemplo, `$name`, `$phone` e `$email` guardam dados do contato. Na `UserEntity`, `$name`, `$email`, `$type` e `$active` representam dados e estado do usuário. O `ContactController` também possui atributos como `$contactService` e `$userId`.

Os **métodos** definem os comportamentos. Exemplos incluem `fill()`, `getName()` e `validationError()` nas entidades; `create()` e `update()` no `ContactService`; e `insert()` e `findById()` no `ContactRepository`. A classe `Auth` também oferece métodos estáticos, como `login()` e `logout()`.

## 6. Como foi aplicado o encapsulamento?

Os atributos das entidades são declarados como `private`, impedindo o acesso direto a partir de outras classes. Seu preenchimento ocorre por métodos como `fill()`, e a leitura por métodos como `getName()` e `getEmail()`.

Alguns detalhes também ficam em métodos privados, como `validatePhone()` na `ContactEntity` e `redirect()` nos controllers. Isso mantém esses detalhes internos e expõe as operações públicas necessárias.

O preenchimento e a validação são operações separadas: chamar `fill()` por si só não garante que os dados sejam válidos. Os serviços chamam a validação antes de persistir os dados.

## 7. Como a organização MVC contribuiu para estruturar o projeto?

A organização inspirada em **Model-View-Controller (MVC)** separa a apresentação do processamento e do acesso aos dados:

- **Model:** a pasta [Models](app/Models/) contém entidades, serviços, interfaces e repositórios.
- **View:** a pasta [Views](app/Views/) contém as telas e seus componentes.
- **Controller:** a pasta [Controller](app/Controller/) recebe as ações e coordena as respostas.

Por exemplo, na listagem de contatos, o `ContactController` solicita os dados ao `ContactService`, que utiliza o `ContactRepository`, e depois carrega a view de listagem. A separação ajuda a localizar e modificar cada parte sem concentrar todo o fluxo no mesmo arquivo.

## 8. Foi utilizada interface? Se sim, qual foi sua finalidade?

Sim. Foram criadas [ContactRepositoryInterface](app/Models/Contacts/ContactRepositoryInterface.php) e [UserRepositoryInterface](app/Models/Users/UserRepositoryInterface.php). Elas definem os métodos que os respectivos repositórios devem implementar, como consulta e inserção.

Esses contratos padronizam as operações de persistência e podem servir de base para outras implementações. Porém, atualmente os serviços instanciam os repositórios concretos diretamente; portanto, as interfaces ainda não eliminam esse acoplamento nem permitem trocar a implementação sem alterar os serviços.

## 9. Foi utilizada herança? Se sim, qual foi sua finalidade? Se não, justifique.

Sim. `ContactController`, `AuthController` e `UserController` herdam de `BaseController`, que reúne a conexão e métodos HTTP compartilhados. Os repositórios usam `implements` para cumprir interfaces; isso implementa um contrato, não herda comportamento de uma classe.

Essa classe-base evita repetir a verificação de requisição POST e a lógica de redirecionamento nos controllers.

## 10. Na sua opinião, qual das duas versões apresenta melhor organização e por quê?

Na minha avaliação, a versão orientada a objetos apresenta melhor organização porque distribui apresentação, controle do fluxo, regras e persistência em partes identificáveis. Isso facilita encontrar responsabilidades e evoluir funcionalidades, especialmente com a inclusão de usuários e autenticação.

A versão procedural é mais simples de acompanhar para um CRUD pequeno, mas a concentração de tarefas nos mesmos arquivos tende a dificultar a manutenção conforme a aplicação cresce. A versão POO também tem espaço para melhorar, como reduzir o acoplamento dos serviços aos repositórios concretos.

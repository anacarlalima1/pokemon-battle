# Pokémon Battle

Aplicação web para simular uma batalha entre dois Pokémons usando a [PokéAPI](https://pokeapi.co/).

O usuário informa o nome de dois Pokémons, o backend consulta a PokéAPI, extrai o HP de cada um e retorna o resultado da batalha. O Pokémon com maior HP vence. Caso os dois tenham o mesmo HP, o resultado é empate.

A aplicação possui frontend em Nuxt com uma interface visual inspirada em jogos Pokémon, exibindo sprites animados, tipos, HP e o resultado da batalha.

---

## Tecnologias utilizadas

### Backend

- PHP 8.3
- Laravel
- Laravel HTTP Client
- PHPUnit/Pest para testes automatizados

### Frontend

- Nuxt
- Vue
- TypeScript
- CSS componentizado com `<style scoped>`

### Ambiente

- Docker
- Docker Compose
- PokéAPI

---

## Por que essas tecnologias foram escolhidas

O **Laravel** foi escolhido pela produtividade na criação de APIs, validações, tratamento de erros e testes automatizados.

O **Nuxt/Vue** foi escolhido para criar uma interface web moderna, componentizada e com boa experiência visual para o usuário.

O **Docker** foi utilizado para facilitar a execução do projeto, evitando dependência de configurações locais da máquina da pessoa avaliadora.

A **PokéAPI** é utilizada como fonte externa de dados dos Pokémons.

---

## Funcionalidades

- Informar dois Pokémons para batalha.
- Normalizar os nomes antes da consulta:
  - remove espaços extras;
  - converte para minúsculo;
  - trata capitalização variada.
- Consultar a PokéAPI.
- Validar se os Pokémons existem.
- Extrair o HP de cada Pokémon.
- Comparar os HPs.
- Exibir:
  - nome dos Pokémons;
  - HP;
  - sprite/GIF animado;
  - tipos;
  - vencedor ou empate;
  - diferença de HP do vencedor.
- Tratar erros de forma amigável.

---

## Regra da batalha

A batalha é decidida exclusivamente pelo valor de HP.

```text
Se HP do Pokémon 1 > HP do Pokémon 2:
    Pokémon 1 vence

Se HP do Pokémon 2 > HP do Pokémon 1:
    Pokémon 2 vence

Se HPs forem iguais:
    Empate
```

Exemplo:

```text
pikachu: 35 HP
charizard: 78 HP

Resultado:
charizard venceu com 78 HP contra 35 HP.
```

---

## Estrutura do projeto

```text
desafio-ateliware/
├── backend/
│   ├── Dockerfile
│   ├── entrypoint.sh
│   └── src/
│       ├── app/
│       │   ├── DTOs/
│       │   ├── Exceptions/
│       │   ├── Http/
│       │   └── Services/
│       ├── routes/
│       ├── tests/
│       ├── composer.json
│       └── .env.example
│
├── frontend/
│   ├── Dockerfile
│   ├── entrypoint.sh
│   └── src/
│       ├── app/
│       │   ├── components/
│       │   ├── composables/
│       │   ├── types/
│       │   └── app.vue
│       ├── public/
│       ├── package.json
│       └── nuxt.config.ts
│
├── docker-compose.yml
├── .gitignore
└── README.md
```

---

## Principais decisões técnicas

### Backend

A regra da batalha foi mantida no backend para centralizar a lógica de negócio.

O backend foi separado em responsabilidades:

```text
BattleController
→ recebe a requisição HTTP e retorna a resposta JSON.

BattleRequest
→ valida os dados de entrada.

PokemonService
→ consulta a PokéAPI, normaliza o nome informado e transforma os dados externos em um DTO.

BattleService
→ aplica a regra da batalha.

PokemonDTO
→ padroniza os dados retornados ao frontend.

Exceptions
→ tratam erros específicos da integração com a PokéAPI.
```

Essa separação evita que o controller concentre regra de negócio e facilita testes.

### Frontend

O frontend foi separado em componentes:

```text
BattleForm.vue
→ formulário para informar os Pokémons.

PokemonCard.vue
→ card visual de cada Pokémon.

BattleResult.vue
→ resultado da batalha.

useBattleApi.ts
→ comunicação com o backend.

battle.ts
→ tipos TypeScript da resposta da API.
```

A interface foi feita com uma proposta visual inspirada em jogos Pokémon, usando cards, cores fortes, sprites animados e destaque para o vencedor.

---

## Tratamento de erros

A aplicação trata os seguintes cenários:

### Pokémon inexistente

Quando a PokéAPI retorna `404`, o backend responde com uma mensagem clara:

```json
{
  "message": "O Pokémon 'pikachuuu' não foi encontrado."
}
```

### Falha de rede ou PokéAPI indisponível

Caso não seja possível consultar a PokéAPI, o backend retorna erro apropriado:

```json
{
  "message": "Não foi possível consultar a PokéAPI no momento. Tente novamente mais tarde."
}
```

### Resposta inesperada da PokéAPI

Se a PokéAPI retornar uma estrutura sem o campo esperado de HP, nome ou dados principais, a aplicação retorna uma mensagem específica:

```json
{
  "message": "A resposta da PokéAPI para 'pikachu' está em um formato inesperado."
}
```

### Campos obrigatórios

Caso o usuário não informe um dos Pokémons, o Laravel retorna erro de validação com status `422`.

No frontend, todos os erros são exibidos de forma amigável para o usuário.

---

## Variáveis de ambiente

### Backend

Arquivo:

```text
backend/src/.env
```

Exemplo:

```env
APP_NAME="Pokemon Battle"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

POKEAPI_URL=https://pokeapi.co/api/v2
FRONTEND_URL=http://localhost:3000
```

### Frontend

Arquivo:

```text
frontend/src/.env
```

Exemplo:

```env
NUXT_PUBLIC_API_BASE_URL=http://localhost:8000/api
```

---

## Como executar o projeto com Docker

### Pré-requisitos

Antes de começar, tenha instalado:

- Docker
- Docker Compose
- Git

---

### 1. Clonar o repositório

```bash
git clone https://github.com/anacarlalima1/pokemon-battle
cd desafio-ateliware
```

---

### 2. Criar arquivos de ambiente

Copie o `.env.example` do backend:

```bash
cp backend/src/.env.example backend/src/.env
```

Caso o frontend tenha `.env.example`, copie também:

```bash
cp frontend/src/.env.example frontend/src/.env
```

Se não existir `.env.example` no frontend, crie o arquivo:

```bash
echo "NUXT_PUBLIC_API_BASE_URL=http://localhost:8000/api" > frontend/src/.env
```

---

### 3. Subir os containers

```bash
docker compose up --build
```

Na primeira execução, o Docker pode demorar alguns minutos para instalar as dependências do backend e do frontend.

---

### 4. Acessar a aplicação

Frontend:

```text
http://localhost:3000
```

Backend:

```text
http://localhost:8000
```

---

## Como testar a API manualmente

A rota principal da API é:

```text
POST /api/battles
```

Exemplo com `curl`:

```bash
curl -i -X POST http://localhost:8000/api/battles \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"pokemon_one":"ditto","pokemon_two":"pikachu"}'
```

Exemplo de resposta:

```json
{
  "pokemon_one": {
    "name": "ditto",
    "hp": 48,
    "sprite": "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/132.png",
    "animated_sprite": "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/132.gif",
    "types": ["normal"]
  },
  "pokemon_two": {
    "name": "pikachu",
    "hp": 35,
    "sprite": "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png",
    "animated_sprite": "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/25.gif",
    "types": ["electric"]
  },
  "result": {
    "type": "winner",
    "winner": "ditto",
    "message": "ditto venceu com 48 HP contra 35 HP."
  }
}
```

---

## Como executar os testes

Os testes automatizados estão no backend.

Com os containers rodando, execute:

```bash
docker compose exec backend php artisan test
```

Ou, para rodar somente os testes da batalha:

```bash
docker compose exec backend php artisan test tests/Feature/PokemonBattleTest.php
```

---

## Cenários cobertos pelos testes

Os testes automatizados cobrem:

- Pokémon 1 vencendo por maior HP.
- Pokémon 2 vencendo por maior HP.
- Empate quando os HPs são iguais.
- Erro quando um Pokémon não existe.
- Validação de campos obrigatórios.
- Normalização de nomes com espaços e capitalização variada.
- Falha de comunicação com a PokéAPI.
- Resposta inesperada da PokéAPI.

Os testes utilizam `Http::fake()` para simular a PokéAPI e não depender de chamadas externas durante a execução.

---

## Comandos úteis

Subir containers:

```bash
docker compose up --build
```

Subir em segundo plano:

```bash
docker compose up -d --build
```

Parar containers:

```bash
docker compose down
```

Ver logs do backend:

```bash
docker compose logs -f backend
```

Ver logs do frontend:

```bash
docker compose logs -f frontend
```

Executar comandos no backend:

```bash
docker compose exec backend sh
```

Executar comandos no frontend:

```bash
docker compose exec frontend sh
```

Limpar cache do Laravel:

```bash
docker compose exec backend php artisan optimize:clear
```

Rodar testes:

```bash
docker compose exec backend php artisan test
```

---

## Observações

- O projeto não utiliza banco de dados para persistência, pois a regra do desafio não exige armazenamento.
- A consulta à PokéAPI é feita em tempo real.
- O backend usa `SESSION_DRIVER=file` para evitar dependência de tabela de sessões.
- O frontend consome apenas a API Laravel, mantendo a regra de negócio no backend.
- Os sprites animados são obtidos da própria resposta da PokéAPI.
- Caso um sprite animado não exista, a aplicação utiliza o sprite estático como fallback.

---

## Histórico Git

O desenvolvimento foi organizado em commits pequenos e descritivos, separando configuração, backend, frontend, testes e documentação.

Exemplos de commits:

```text
chore: configure docker environment
feat: implement pokemon battle api
feat: create game-style pokemon battle interface
feat: improve pokeapi error handling
test: cover pokemon battle scenarios
docs: add project setup instructions
```

---

## Autor

Desenvolvido como parte de um desafio técnico.
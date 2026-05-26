#!/bin/sh

set -e

cd /var/www/html

if [ ! -f "artisan" ]; then
    echo "Laravel não encontrado em backend/src."
    echo "Crie o projeto Laravel antes de subir o container."
    exit 1
fi

if [ ! -d "vendor" ]; then
    echo "Instalando dependências do Composer..."
    composer install
fi

if [ ! -f ".env" ]; then
    echo "Criando .env a partir do .env.example..."
    cp .env.example .env
fi

if ! grep -q "POKEAPI_URL=" .env; then
    echo "POKEAPI_URL=https://pokeapi.co/api/v2" >> .env
fi

if grep -q "APP_KEY=$" .env || ! grep -q "APP_KEY=base64:" .env; then
    echo "Gerando APP_KEY..."
    php artisan key:generate
fi

php artisan config:clear

echo "Backend Laravel rodando em http://localhost:8000"

php artisan serve --host=0.0.0.0 --port=8000
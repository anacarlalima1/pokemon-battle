#!/bin/sh

set -e

cd /app

if [ ! -f "package.json" ]; then
    echo "Nuxt não encontrado em frontend/src."
    echo "Crie o projeto Nuxt antes de subir o container."
    exit 1
fi

if [ ! -d "node_modules" ]; then
    echo "Instalando dependências do NPM..."
    npm install
fi

if [ ! -f ".env" ]; then
    echo "Criando .env do frontend..."
    echo "NUXT_PUBLIC_API_BASE_URL=http://localhost:8000/api" > .env
fi

echo "Frontend Nuxt rodando em http://localhost:3000"

npm run dev -- --host 0.0.0.0
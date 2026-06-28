#!/usr/bin/env bash

set -Eeuo pipefail

ENVIRONMENT="${1:?Informe homolog ou production}"
IMAGE="${2:?Informe o nome da imagem Docker}"
ENV_FILE="${3:?Informe o arquivo de configuração}"

case "$ENVIRONMENT" in
    homolog)
        PROJECT_NAME="receitas-homolog"
        ;;
    production)
        PROJECT_NAME="receitas-production"
        ;;
    *)
        echo "Ambiente inválido: $ENVIRONMENT"
        exit 1
        ;;
esac

compose() {
    APP_IMAGE="$IMAGE" docker compose \
        --project-name "$PROJECT_NAME" \
        --env-file "$ENV_FILE" \
        -f compose.app.yml \
        "$@"
}

wait_for_database() {
    local database_id
    local status

    database_id="$(compose ps -q db)"

    for attempt in $(seq 1 30); do
        status="$(
            docker inspect \
                --format='{{if .State.Health}}{{.State.Health.Status}}{{else}}starting{{end}}' \
                "$database_id" 2>/dev/null || true
        )"

        if [ "$status" = "healthy" ]; then
            echo "Banco de dados disponível."
            return 0
        fi

        echo "Aguardando banco: tentativa $attempt de 30..."
        sleep 3
    done

    echo "O banco de dados não ficou disponível."
    compose logs db
    return 1
}

echo "Criando o banco de $ENVIRONMENT..."
compose up -d db

wait_for_database

echo "Executando migrations e seeders..."
compose run --rm app php artisan migrate --seed --force

echo "Iniciando a aplicação..."
compose up -d app

echo "Gerando caches do Laravel..."
compose exec -T app php artisan config:clear
compose exec -T app php artisan config:cache
compose exec -T app php artisan view:clear
compose exec -T app php artisan view:cache

echo "Ambiente criado:"
compose ps
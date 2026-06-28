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
            return 0
        fi

        echo "Aguardando banco: tentativa $attempt de 30..."
        sleep 3
    done

    compose logs db
    return 1
}

echo "Garantindo que o banco esteja ativo..."
compose up -d db

wait_for_database

echo "Executando migrations pendentes..."
compose run --rm app php artisan migrate --force

echo "Atualizando o contêiner da aplicação..."
compose up -d --no-deps --force-recreate app

echo "Atualizando caches do Laravel..."
compose exec -T app php artisan config:clear
compose exec -T app php artisan config:cache
compose exec -T app php artisan view:clear
compose exec -T app php artisan view:cache

echo "Deploy finalizado:"
compose ps
#!/usr/bin/env bash

set -Eeuo pipefail

CI_IMAGE="${1:?Informe a imagem de integração}"
RAW_BUILD_ID="${2:-local}"
RESULT_DIRECTORY="${3:-build}"

BUILD_ID="$(
    printf '%s' "$RAW_BUILD_ID" \
        | tr -c '[:alnum:]_.-' '-'
)"

NETWORK="receitas-ci-${BUILD_ID}"
DATABASE_CONTAINER="receitas-ci-db-${BUILD_ID}"
TEST_CONTAINER="receitas-ci-test-${BUILD_ID}"

mkdir -p "$RESULT_DIRECTORY"

cleanup() {
    docker rm -f "$TEST_CONTAINER" >/dev/null 2>&1 || true
    docker rm -f "$DATABASE_CONTAINER" >/dev/null 2>&1 || true
    docker network rm "$NETWORK" >/dev/null 2>&1 || true
}

trap cleanup EXIT

echo "Criando rede temporária de integração..."
docker network create "$NETWORK"

echo "Iniciando MySQL temporário de testes..."
docker run -d \
    --name "$DATABASE_CONTAINER" \
    --network "$NETWORK" \
    --network-alias db \
    -e MYSQL_DATABASE=receitas_test \
    -e MYSQL_USER=receitas_test \
    -e MYSQL_PASSWORD=receitas_test_password \
    -e MYSQL_ROOT_PASSWORD=receitas_root_password \
    mysql:8.0

for attempt in $(seq 1 30); do
    if docker exec "$DATABASE_CONTAINER" \
        mysqladmin ping \
        -h localhost \
        -u root \
        -preceitas_root_password \
        --silent; then
        break
    fi

    echo "Aguardando MySQL de testes: tentativa $attempt de 30..."
    sleep 3
done

docker exec "$DATABASE_CONTAINER" \
    mysqladmin ping \
    -h localhost \
    -u root \
    -preceitas_root_password \
    --silent

echo "Criando contêiner de testes..."

docker create \
    --name "$TEST_CONTAINER" \
    --network "$NETWORK" \
    -e APP_ENV=testing \
    -e APP_KEY="base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=" \
    -e APP_DEBUG=true \
    -e DB_CONNECTION=mysql \
    -e DB_HOST=db \
    -e DB_PORT=3306 \
    -e DB_DATABASE=receitas_test \
    -e DB_USERNAME=receitas_test \
    -e DB_PASSWORD=receitas_test_password \
    -e CACHE_DRIVER=array \
    -e SESSION_DRIVER=array \
    -e QUEUE_CONNECTION=sync \
    "$CI_IMAGE" \
    sh -lc \
    "php artisan migrate:fresh --force &&
     vendor/bin/phpunit --log-junit /tmp/junit.xml"

set +e

docker start -a "$TEST_CONTAINER"
TEST_STATUS=$?

set -e

docker cp \
    "$TEST_CONTAINER:/tmp/junit.xml" \
    "$RESULT_DIRECTORY/junit.xml" || true

if [ ! -f "$RESULT_DIRECTORY/junit.xml" ]; then
    echo "O relatório JUnit não foi gerado."
    exit 1
fi

TEST_COUNT="$(
    grep -m1 -o 'tests="[0-9]*"' \
        "$RESULT_DIRECTORY/junit.xml" \
        | grep -o '[0-9]*' \
        || echo 0
)"

echo "Quantidade de testes executados: $TEST_COUNT"

if [ "$TEST_COUNT" -lt 20 ]; then
    echo "O projeto precisa executar pelo menos 20 testes."
    exit 1
fi

exit "$TEST_STATUS"
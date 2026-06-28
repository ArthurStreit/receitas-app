#!/usr/bin/env bash

set -Eeuo pipefail

CI_IMAGE="${1:?Informe a imagem de integração}"
RAW_BUILD_ID="${2:-local}"
RESULT_DIRECTORY="${3:-build}"

BUILD_ID="$(
    printf '%s' "$RAW_BUILD_ID" \
        | tr -c '[:alnum:]_.-' '-'
)"

CONTAINER_NAME="receitas-quality-${BUILD_ID}"

mkdir -p "$RESULT_DIRECTORY"

cleanup() {
    docker rm -f "$CONTAINER_NAME" >/dev/null 2>&1 || true
}

trap cleanup EXIT

docker create \
    --name "$CONTAINER_NAME" \
    "$CI_IMAGE" \
    sh -lc '
        vendor/bin/phpcs \
            --standard=phpcs.xml \
            --report=checkstyle \
            --report-file=/tmp/phpcs.xml

        STATUS=$?

        vendor/bin/phpcs \
            --standard=phpcs.xml \
            --report=full \
            --report-file=/tmp/phpcs.txt || true

        exit $STATUS
    '

set +e

docker start -a "$CONTAINER_NAME"
QUALITY_STATUS=$?

set -e

docker cp \
    "$CONTAINER_NAME:/tmp/phpcs.xml" \
    "$RESULT_DIRECTORY/phpcs.xml" || true

docker cp \
    "$CONTAINER_NAME:/tmp/phpcs.txt" \
    "$RESULT_DIRECTORY/phpcs.txt" || true

exit "$QUALITY_STATUS"
pipeline {
    agent any

    options {
        timestamps()
        disableConcurrentBuilds()
    }

    stages {
        stage('Obter código') {
            steps {
                checkout scm
            }
        }

        stage('Preparar build') {
            steps {
                script {
                    env.SHORT_COMMIT = sh(
                        script: 'git rev-parse --short=8 HEAD',
                        returnStdout: true
                    ).trim()

                    env.CI_IMAGE =
                        "receitas-app-ci:${env.SHORT_COMMIT}"

                    env.APP_IMAGE =
                        "receitas-app:${env.SHORT_COMMIT}"
                }

                sh 'rm -rf build && mkdir -p build'
            }
        }

        stage('Build da integração') {
            steps {
                sh '''
                    docker build \
                        --target ci \
                        -t "$CI_IMAGE" \
                        .
                '''
            }
        }

        stage('Testes automatizados') {
            steps {
                sh '''
                    bash scripts/ci-test.sh \
                        "$CI_IMAGE" \
                        "$BUILD_TAG" \
                        build
                '''
            }
        }

        stage('Análise de qualidade') {
            steps {
                sh '''
                    bash scripts/quality.sh \
                        "$CI_IMAGE" \
                        "$BUILD_TAG" \
                        build
                '''
            }
        }

        stage('Build da aplicação') {
            steps {
                sh '''
                    docker build \
                        --target production \
                        -t "$APP_IMAGE" \
                        .
                '''
            }
        }

        stage('Atualizar homologação') {
            when {
                branch 'homolog'
            }

            steps {
                sh '''
                    bash scripts/deploy.sh \
                        homolog \
                        "$APP_IMAGE" \
                        /run/receitas-secrets/homolog.env
                '''
            }
        }

        stage('Aprovar produção') {
            when {
                branch 'main'
            }

            steps {
                input message:
                    'Os testes passaram. Publicar em produção?',
                    ok: 'Publicar'
            }
        }

        stage('Atualizar produção') {
            when {
                branch 'main'
            }

            steps {
                sh '''
                    bash scripts/deploy.sh \
                        production \
                        "$APP_IMAGE" \
                        /run/receitas-secrets/production.env
                '''
            }
        }
    }

    post {
        always {
            junit allowEmptyResults: true,
                  testResults: 'build/junit.xml'

            archiveArtifacts allowEmptyArchive: true,
                             artifacts: 'build/**'
        }

        cleanup {
            sh 'docker image prune -f || true'
        }
    }
}
pipeline {
    agent any

    environment {
        PHP_VERSION = '7.4'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        
        stage('Install Dependencies') {
            steps {
                sh 'composer install'
            }
        }
        
        stage('Unit Tests') {
            steps {
                sh 'vendor/bin/phpunit'
            }
        }
        
        stage('Build') {
            steps {
                script {
                    // Si vous utilisez Docker pour le déploiement
                    def image = docker.build("myapp:${env.BUILD_ID}")
                    image.push()
                }
            }
        }
    }

    post {
        always {
            archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true
            junit 'reports/**/*.xml'
        }
    }
}

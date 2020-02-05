pipeline {
    agent any
    environment {
        PROJECT = 'bfin--zaplate'
    }
    stages {
        stage('branch: master') {
            when {
                branch 'master'
            }
            steps {
                sh 'docker-phpunit -u 5.5 7.4'
            }
            post {
                success {
                    sh 'jenkins-postproc'
                }
            }
        }
    }
}

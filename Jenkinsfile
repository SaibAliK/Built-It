// Define variable
def PATH = "/var/www/html/alpha3/seven-project"

pipeline {
  agent any
  //agent {label 'slave-node'}

  stages {

    stage('COPY CODE') {
      steps {

          echo "Project to be deployed in ${PATH}"

          // Copy Code to Target Directory
          sh "sudo cp -rf $WORKSPACE/* ${PATH}"

      }

    }

    stage('PERMISSIONS SETUP') {
      steps {

          // Set ownership of Project Directory
          sh "sudo chown -R www-data:www-data ${PATH}"

          //  Set permissions for storage directory
          sh "sudo chmod -R 775 ${PATH}/storage"

          // Set permissions for Cache Directory
          sh "sudo chmod -R 775 ${PATH}/bootstrap/cache"

       }

    }

    stage('DATABASE MIGRATIONS') {
      steps {

          echo "Run DATABASE MIGRATIONS if needed"
          // uncomment the following line
          //sh "/usr/bin/php ${PATH}/artisan  migrate:fresh --seed"
          // only migrate tables
        //   sh "php ${PATH}/artisan  migrate"

        }
    }

    stage('CACHE CLEAR') {
      steps {

          echo "Run CACHE CLEAR if needed"
          // uncomment the following line
          //sh "/usr/bin/php ${PATH}/artisan cache:clear"

        }
     }

 }

    environment {
        //comma separated list for email receipients
        EMAIL_TO = 'danish.s@mytechnology-team.com,khurram.mytech@gmail.com'
    }


    post {
        success {
            echo 'posting success to GitLab'
            updateGitlabCommitStatus(name: 'jenkins-build', state: 'success')

//            echo 'sending emails'
//            emailext body: 'Check console output at $BUILD_URL to view the results. \n\n ${CHANGES} \n\n -------------------------------------------------- \n${BUILD_LOG, maxLines=100, escapeHtml=false}',
//                    to: "${EMAIL_TO}",
//                    recipientProviders: [developers()],
//                    subject: 'Build Success in Jenkins: $PROJECT_NAME - #$BUILD_NUMBER'
        }
        failure {
            echo 'posting failure to GitLab'
            updateGitlabCommitStatus(name: 'jenkins-build', state: 'failed')

//            echo 'sending emails'
//            emailext body: 'Check console output at $BUILD_URL to view the results. \n\n ${CHANGES} \n\n -------------------------------------------------- \n${BUILD_LOG, maxLines=100, escapeHtml=false}',
//                    to: "${EMAIL_TO}",
//                    recipientProviders: [developers()],
//                    subject: 'Build Failure in Jenkins: $PROJECT_NAME - #$BUILD_NUMBER'
        }
     }

}


## copy the demo app in your work dir 
 execute command ```sudo chmod -R +777 ./demo-app``` and ```cd ./demo-app```
execute ```docker-compose build``` and ```docker-compose up -d```
## install composer if necessary 
-- `docker exec -it billbox_app bash -c "cd /var/www/html && composer install --no-interaction"`
## To install all db fake data and login cred 
-- `docker exec -it billbox_app php ./demo/app/cli.php main createFakeData`
## ERD file is in ERD folder 
  

# HOW TO RUN CYPRESS TEST #
 #### create a work directory as an exmple ./test in your local machine; go inside test directory and run following command
```yarn add cypress --dev``` (it will install cypress in your local machine)
#### replace your test workdir cypress.json file with application  <app_root_dir>/e2e/cypress.json file  
### move the <app_root_dir>/e2e/cypress/integration/demo_app/demo_app_spec.js to your workdir <your_test_work_dir>/cypress/integration/demo_app/demo_app_spec.js 

Execute this command ```yarn run cypress open```
My local test video available in 'e2e\cypress\videos\'
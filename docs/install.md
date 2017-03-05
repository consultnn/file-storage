## Install

1. [Install Docker](https://github.com/docker/docker/releases)
2. Add yours user in docker group
    `sudo usermod -aG docker $USER`
3. [Install Docker Compose](https://github.com/docker/compose/releases)

Prepare app environment
===========
1. Clone repo in working dir

`git clone git@github.com:consultnn/file-storage.git ./app` 

2. Move in project dir

`cd ./app`

3. Run Docker containers

`docker-compose up -d`

4. Run build scripts  

``` 
./scripts/composer.sh 
./scripts/init.sh 
```  
    
Go to
[http://127.0.0.1:8211](http://127.0.0.1:8211)

Next use [API](./api.md)
V2 under heavy development!
==========================

Docker
===========
1. [Install Docker](https://docs.docker.com/engine/installation/linux/ubuntulinux/#/install)
2. `sudo usermod -aG docker $USER`, re-login
3. [Install docker-compose](https://github.com/docker/compose/releases/latest)

Deploy app
===========
1. Clone repository
`git clone git@git.icc:consultnn/file-storage.git file-storage`
2. Move in project dir
`cd file-storage`
3. Run docker containers
`docker-compose up -d`
6. Run build scripts
`./scripts/composer`

Other documentation
===========
 - [API](docs/api.md)
 - [Configuration](docs/configuration.md)
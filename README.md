This repository contains a Docker Compose file for setting up a basic and functional environment for developing a PHP application.

1. Clone the repository:
```bash
git clone https://github.com/nasserman/basic-php-development-docker.git
```

2. Rename the cloned directory to a new name:
```bash
mv basic-php-development-docker my-project
```

3. Change directory into the cloned directory:
```bash
cd my-project
```

4. Change "container_name," "ports," and "network" in "docker-compose.yml" according to the new project settings.

5. Bring up the container:
```bash
docker compose up -d
```

6. Put your code into the "src" directory.

7. Navigate to "localhost:[selected-port-in-docker-compose.yml]" to access your application.
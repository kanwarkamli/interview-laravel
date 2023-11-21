## Background
### Objective

Design **tests** for use cases and issues that engineers may encounter in system development.

### Usecase

- An administrator wants to get a list of students after logging in.
- At the same time, the administrator wants to confirm the courses which each student registered.

### What you need to do

- Please implement the API listed in `./api/test/openapi.yaml`.
- This API requires login to make requests.
- Use the Seeder to create sample data.
- Also, testing of this API is necessary.
- Ensure that the tests can be run on GitHub Actions.
- Please write readable codes.
- If there is enough time, please also perform static analysis on GitHub Actions.
- If there is enough time, please develop screens.
- Please write down the procedure for starting and running the tests in README.md.

## Solution

The API was developed and designed was done based on the given OpenAPI structure.
GitHub Actions also configured to run the tests and perform static analysis on new commit.

### Stack/Technology used
- PHP 8
- Laravel 10
- Laravel Sanctum
- PestPHP2
- PHPStan

### Deployment

To deploy, clone the repository by running these commands:

```bash
  mkdir toy8 && cd toy8
  git clone https://github.com/kanwarkamli/interview-laravel.git .
  cp .env.example .env
  docker compose up -d
```

### Setting up the app

Once the containers are running, bash into the container and run these commands:

```bash
  docker exec -it toyeight.app bash
  composer install
  cp .env.example .env
  php artisan key:generate
  php artisan migrate --seed
```

### Testing
To run the test, execute the following command while in the container:

```bash
  ./vendor/bin/pest
```

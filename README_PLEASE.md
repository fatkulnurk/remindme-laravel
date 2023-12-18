# RemindMe

This page provides instructions on how to run the application. If you face any issues, please share the details with me.
Thank you!

Content

- About Me
- Branch
- Mission
- URL & Credential
- Installation

## About Me

Hi, I'm Fatkul Nur Koirudin, and you can call me Rudi or Fatkul. You can reach me via email at fatkul@dibumi.com or
through my phone/WhatsApp at 085607100255.

## Branch

Important information.

| CI/CD pipeline State | Branch Name | Workflow                       | Note                                                                                                                                                                                                                                                                               |
|----------------------|-------------|--------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Development          | development | ✅ docker.yml<br/>✅ laravel.yml | Branch for local development.                                                                                                                                                                                                                                                      |
| Quality Assurance    | -           | -                              | No implementation                                                                                                                                                                                                                                                                  |
| Staging              | main        | ✅ laravel-deploy.yml           | Code that has been tested and is considered stable. This is the version of the code that is considered ready for use in production.<br/><br/>All code placed here will be automatically deployed via laravel-deploy.yml using docker compose docker-compose.prod.yml.<br/><br/>Don't forget to check out the https://github.com/fatkulnurk/remindme-laravel/blob/ac894d00c5a5890fcd3d83737f0e874089e8ca99/.github/workflows/README.md?plain=1#L67 |
| Production           | -           | -                              | No implementation                                                                                                                                                                                                                                                                  |

## Mission

| No | Task                                                                                                                                                                                                                                                                                                                                                                                                                                   | Status |
|----|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------|
| 1  | Build the web app based on specification written in `README.md` and [`rest_api.md`](./docs/rest_api.md). **Treat it as an MVP**. For the backend you must use **[Laravel Framework](https://laravel.com/)**. For the frontend you can use any framework you like or even just vanilla HTML, CSS, & Javascript. You can use [Laravel Blade](https://laravel.com/docs/10.x/blade) as well but make sure it completely uses the REST API. | ✅      |
| 2  | Dockerize your system & make sure it can run with full functionality using [Docker Compose](https://docs.docker.com/compose/) in Linux-like environment. We will test your system in Ubuntu or MacOS during review.                                                                                                                                                                                                                    | ✅      |
| 3  | Write automated testing for your backend. At the very minimum you must implement unit testing (not feature testing). If you can write automated testing for your frontend as well, that would be great.                                                                                                                                                                                                                                | ✅      |
| 4  | Implement CI pipeline for your system. We recommend using [Github Actions](https://github.com/features/actions), but you can use any CI tool you like.                                                                                                                                                                                                                                                                                 | ✅      |

## URL & Credential

| Service               | full url               | host      | port  | description                                                                                                                                   |
|-----------------------|------------------------|-----------|-------|-----------------------------------------------------------------------------------------------------------------------------------------------|
| App & Api             | http://127.0.0.1:8066  | 127.0.0.1 | 8066  | email: alice@mail.com, password: 123456,<br/>email: bob@mail.com, password: 123456                                                            |
| MySQL                 |                        | 127.0.0.1 | 3306  | MYSQL_DATABASE: default,<br/>MYSQL_USER: laravel, <br/>MYSQL_PASSWORD: secret, <br/>MYSQL_ROOT_PASSWORD: secret , <br/>SERVICE_NAME: database |
| Mailpit (webmail)     | http://127.0.0.1:48025 | 127.0.0.1 | 48025 | webmail                                                                                                                                       |
| Mailpit (mail server) | http://127.0.0.1:41025 | 127.0.0.1 | 41025 | mail server                                                                                                                                   |

## Installation

[//]: # (Before accessing the website, make sure to build the application using `npm run build`. The build folder is included in)

[//]: # (the .gitignore by default from Laravel team. If you encounter any issues about vite/Compiling Assets, follow the)

[//]: # (tutorial provided at the very end &#40;Compiling Assets&#41;.)

**git clone this project**

```
git clone https://github.com/fatkulnurk/remindme-laravel.git 
```

**Move to the directory where you cloned. for example, like this:**

```text
cd remindme-laravel
```

More or less, later you will find a structure like this (I display hidden files, maybe your place is hidden by default)

```text
./
├── docker
├── docker-compose.ci.yml
├── docker-compose.prod.yml
├── docker-compose.yml
├── docs
├── .git
├── .github
├── .gitignore
├── image.Dockerfile
├── README.md
├── README_PLEASE.md
└── src

```

Run Docker for Development

```shell
docker compose up --build
```

Run Docker for Unit & Feature Test

```shell
docker compose -f docker-compose.ci.yml up --build  --abort-on-container-exit
```

Run Docker for Production

```shell
docker compose -f docker-compose.prod.yml up --build 
```

[//]: # (### deprecated)

[//]: # (**Run Docker**)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose up -d app)

[//]: # (```)

[//]: # ()

[//]: # (**for some case, if you need rebuild, run this**)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose build --no-cache)

[//]: # (```)

[//]: # ()

[//]: # (**install dependency**)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose run --rm composer install)

[//]: # (```)

[//]: # ()

[//]: # (**copy .env.example to .env**)

[//]: # ()

[//]: # (```text)

[//]: # (cp src/.env.example src/.env)

[//]: # (```)

[//]: # ()

[//]: # (**Generate Key**)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose run --rm artisan key:generate)

[//]: # (```)

[//]: # ()

[//]: # (**Migrate database**)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose run --rm artisan migrate:fresh --seed)

[//]: # (```)

[//]: # ()

[//]: # (**Clear Optimize**)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose run --rm artisan optimize:clear)

[//]: # (```)

[//]: # ()

[//]: # (**Run Test &#40;Unit Test & Feature Test&#41;**)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose run --rm artisan test)

[//]: # (```)

[//]: # ()

[//]: # (---)

[//]: # ()

[//]: # (**Dev Server & Compiling Assets**)

[//]: # ()

[//]: # (install dependency &#40;like `npm install`&#41;)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose run --rm npm install)

[//]: # (```)

[//]: # ()

[//]: # (for dev server &#40;like `npm run dev`&#41;, you can run with command:)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose run --rm --service-ports npm run dev)

[//]: # (```)

[//]: # ()

[//]: # (or, if you need build &#40;like `npm run build`&#41;, run this command:)

[//]: # ()

[//]: # (```text)

[//]: # (docker compose run --rm npm run build)

[//]: # (```)

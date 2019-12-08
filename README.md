# Bitrix Project

Заготовка для 1C Bitrix проектов.
todo:
- подумать выпилить Vue 
- подумать выпилить webpack/encore
- Добавить в гайдлайн правила именований модулей и компонентов
- Выпилить twig из зависимостей и гайдлайна
- подумать насчет https://github.com/Mediahero/bitrix-clear-upload

## Создание нового проекта

Стандартно установить или развернуть из бекапа копию Битрикса.

Клонировать репозиторий (за пределами публичной директории веб-сервера). Приоритетно каждый проект размещаем по пути
```sh
/var/repository/название_проета
``` 
Переинициализировать репозиторий: удалить директорию `.git` и выполнить `git init`.

Установить зависимости и "собрать" фронтенд:
```sh
composer install && npm install && npm run encore -- dev
```

Перенести в корень клонированного проекта содержимое директорий `bitrix`, `upload` и `local` 
(не затирая файл `local/php_interface/init.php`).
```
windows:
mklink /j sites\s1\bitrix __project_dir__\bitrix
mklink /j sites\s1\upload __project_dir__\upload
mklink /j sites\s1\local __project_dir__\local

unix:
ln -s __project_dir__\bitrix sites\s1\bitrix
ln -s __project_dir__\upload sites\s1\upload
ln -s __project_dir__\local sites\s1\local
```

В директорию `sites/s1` перенести публичные файлы сайта.

Настроить вебсервер для работы с директорией `sites/s1` либо сделать симлинк вида

```sh
/home/bitrix/www -> /home/bitrix/projectname/sites/s1
```

Создать файл `.env` 

```sh
touch .env
```

Заполнить его данными в соответствии с файлом-образцом `.env.example`

Выполнить команду
windows
```sh
vendor/bin/jedi.bat init
vendor/bin/jedi.bat env:init dev
```

linux
```sh
./vendor/bin/jedi init
./vendor/bin/jedi env:init dev
```

Эта команда скопирует в директорию `bitrix` системные файлы настроек сконфигурированные для работы с 
[переменными окружения](https://github.com/vlucas/phpdotenv), а также настройки 
[шаблонизатора Twig](https://github.com/maximaster/tools.twig) 
и [логгера Monolog](https://github.com/bitrix-expert/monolog-adapter)


Установить [модуль миграций](https://github.com/arrilot/bitrix-migrations)

```sh
php migrator install
```

Добавляем автоматическую генерацию миграций в init.php
```
Arrilot\BitrixMigrations\Autocreate\Manager::init($_SERVER["DOCUMENT_ROOT"].'/../../migrations');
```

Доустановить модуль [Базовых Битрикс компонентов](https://github.com/bitrix-expert/bbc). в административном интефейсе: 

`Marketplace > Установленные решения > ББК (bex.bbc)`

В настройках сайта необходимо указать "Путь к корневой папке веб-сервера для этого сайта"

Удалить файл README.md
Переименовать файл README.md.example в README.md и заполнить его актуальными данными 

Заполнить файл package.json актуальными данными 

## Бэкенд

Composer и PSR-4 автозагрузка классов из директории `local/classes`. Пространство имен `\Local\ `

### Используемые пакеты:

- [arrilot/bitrix-migrations](https://github.com/arrilot/bitrix-migrations)
- [arrilot/bitrix-models](https://github.com/arrilot/bitrix-models)
    - [illuminate/database](https://github.com/illuminate/database)
    - [illuminate/events](https://github.com/illuminate/events)
- [bitrix-expert/bbc](https://github.com/bitrix-expert/bbc)
- [bitrix-expert/monolog-adapter](https://github.com/bitrix-expert/monolog-adapter)
- [bitrix-expert/tools](https://github.com/bitrix-expert/tools)
- [maximaster/tools.twig](https://github.com/maximaster/tools.twig)
- [notamedia/console-jedi](https://github.com/notamedia/console-jedi)
- [kint-php/kint](https://github.com/kint-php/kint) и [kint-php/kint-twig](https://github.com/kint-php/kint-twig)  
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)

### Контроль качества

Для проверки пхп-кода используется [squizlabs/PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).

Код проверятся в соответствии с набором правил, описанных в файле [ruleset.xml](ruleset.xml).

На данный момент, это стандарт PSR-2 
([рус.](https://svyatoslav.biz/misc/psr_translation/#_PSR-2)/[англ.](http://www.php-fig.org/psr/psr-2/)),
а также наличие PHPDoc-комментариев.

Проверяются файлы из директорий [local/classes](local/classes) и [local/components](local/components) 
(за исключением файлов `template.php`)

Проверка осуществляется командой (это запуск утилиты `phpcs` с предустановленными параметрами) 

```sh
composer run lint:php
```

Также есть возможность исправить часть обнаруженных ошибок утилитой `phpcbf`

```sh
composer run fix:php
```

## Фронтенд

В качестве "сборщика" изпользуется [symfony/webpack-encore](https://github.com/symfony/webpack-encore). 

По-умолчанию файлы фронтенда должны располагаться в директории `local/assets`.

Это можно переопределить в файле конфигурации [webpack.config.js](./webpack.config.js) 

Основные команды:

```sh
npm run encore -- dev          # запустить сборку один раз
npm run encore -- dev --watch  # запустить сборку в режиме слежения за файлами
npm run encore -- production   # запустить сборку для продакшена
```

### Технологии

- SCSS ([рус.](https://sass-scss.ru/guide/)/[англ.](http://sass-lang.com/guide))
- "Современный" Javascript ([рус](https://learn.javascript.ru/es-modern)/[англ](https://github.com/metagrover/ES6-for-humans))
    - [DOM-based Router](https://github.com/roots/sage/blob/master/resources/assets/scripts/util/Router.js)
    - [Vue JS](https://vuejs.org/)
    
#### Vue

Мини-модуль [vueInvoker](local/assets/scripts/util/vueInvoker.js) 
предназначен для инициализации Vue компонентов на странице.
Он упрощает использование Vueклассическом веб-приложении, когда нет возможности 
использовать один "корневой" экземпляр `Vue` (Как, например, это устроено в одностраничных приложениях).

#### Использование:

Вывести на страницу элемент-плейсхолдер для компонента:

```html
<div class="vue-component" data-component="DemoApp" data-initial='{"test": "data"}'></div>
```

Создать соответствущий Vue-компонент (в директории `local/assets/scripts/vue/components/`:


```html
<template>
    <div class="demo-app">
        {{ hello }}

        {{ initial.test }}

    </div>
</template>

<script>
    export default {
      data() {
        return {
          hello: 'World',
        };
      },
      props: ['initial'],
    };
</script>
```

Добавить его в Коллекцию `local/assets/scripts/vue/collection.js`:

```js
import DemoApp from './components/DemoApp.vue';

export default {
  DemoApp,
};
``` 
### Контроль качества

JS-файлы проверяются на соответствие стандарту [airbnb](https://github.com/airbnb/javascript) 
утилитой [ESLint](https://eslint.org). Конфигурация линтера - файл [.eslintrc](.eslintrc)

```sh
npm run lint:scripts  # показать ошибки
npm run fix:scripts   # исправить ошибки
```

SCSS-файлы проверяются утилитой [stylelint](https://stylelint.io/). 
Основа - набор правил [sass-guidelines](https://github.com/bjankord/stylelint-config-sass-guidelines). 
Конфигурация - файл [.stylelintrc](.stylelintrc)

```sh
npm run lint:styles  # показать ошибки
npm run fix:styles   # исправить ошибки
```

За исправление стилевых файлов отвечает пакет [stylefmt](https://github.com/morishitter/stylefmt)


## Многосайтовость

Структура проекта напоминает _заранее_ настроенную 
[многосайтовость на разных доменах](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=103&LESSON_ID=287) 
с отдельными директориями для каждого сайта. Файлы ядра подключаются _относительными_ символическими ссылками.
Для добавления нового сайта нужно создать новую директорю в `./sites/`(лучше всего если ее название будет 
соответствовать коду нового сайта). И добавить в нее ссылки на необходимые файлы и директории:

```
mkdir sites/s2             # создать директорию для дополнительного сайта	
cd sites/s2                # перейти в нее
ln -s ../../bitrix bitrix  # и
ln -s ../../local local    # добавить 
ln -s ../../upload upload  # ссылки     
``` 

Далее необходимо настроить веб-сервер для работы с новым сайтом.

Настроить Путь к корневой папке веб-сервера для каждого сайта и доменное имя

## Разное
На дев версии не забыть закрыть robots.txt 
```
User-agent: *
Disallow: /
```

[Версионирование и деплой](https://github.com/K0rINf/bitrix-project/wiki/Версионирование-и-деплой)
 
[Гайдлайн разработки](https://github.com/K0rINf/bitrix-project/wiki/Гайдлайн)

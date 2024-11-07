# Тестовое задание в команду Дром.Контент

# Локальное окружение
## Подготовка локального окружения

Генерируем ssl-сертификат через [mkcert](https://github.com/FiloSottile/mkcert):

```bash
mkcert \
  -key-file docker/traefik/certs/key.pem \
  -cert-file docker/traefik/certs/cert.pem \
  drom.localhost '*.drom.localhost'
```

ПРИМЕЧАНИЕ
Для автоматизации рабочего процесса на проекте используется Make-файл.
Рекомендуется добавить алиас в файл конфигурации `.bashrc` или `.zshrc`:

```bash
alias m='make -s --'
```

**В тексте далее будет использоваться данный алиас.**
:::

Запускаем окружение:

```bash
m start
```
Устанавливаем зависимости через Composer:
```bash
m composer install
```

Car Catalog API
![full-api.png](/docs/image/full-api.png)

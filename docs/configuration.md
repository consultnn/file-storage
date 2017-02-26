Настройка
===

### Настройка проекта
В файле `./src/app/config/settings.php` вы должны добавить добавить свое приложение
для которого будут загружаться файлы

```php
...
'projects' => [
    // Название вашего проекта
    'my-project-name' => [
        // Локальные настройки файловой системы
        'storage' => [
            // Префикс для ваших изображений
            // В данном случае файлы будут находится по пути
            // {storage.directory}/my-project-name/
            'prefix' => 'my-project-name'
        ],
        // Секретный ключ для загрузки изображений
        'uploadToken' => 'N3edBMSnQrakH9nBK98Gmmrz367JxWCT',
        // Ключ аутентификации для скачивания изображений
        // Для большей информации обратитесь к документации [thephpleague/glide](http://glide.thephpleague.com/1.0/config/security/)
        'downloadSignKey' => 'v-LK4WCdhcfcc%jt*VC2cj%nVpu+xQKvLUA%H86kRVk_4bgG8&CWM#k*b_7MUJpmTc=4GFmKFp7=K%67je-skxC5vz+r#xT?62tT?Aw%FtQ4Y3gvnwHTwqhxUh89wCa_',
    ]
],
...
```
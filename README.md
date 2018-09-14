#### Sunfox.ee website v4
Built with:
* FatFreeFramework (PHP) - https://fatfreeframework.com/3.6/home
* Composer packs: phpmailer/phpmailer, google/recaptcha

### Munin bot actions configuration
## Создание кнопок:
```php
$button1_1 = [null, "white", "white"];
$button1_2 = [["animals" => 'Pig'], "blue", "blue"];
$button2_1 = [["animals" => 'Cow'], "green", "green"];
$button2_2 = [["animals" => 'Chicken'], "red", "red"];
```
# Как описываются кнопки ?
Параметр 1: Payload - может принимать значение: ассоциативный массив или null\
Параметр 2: Надпись на кнопке - текст\
Параметр 3: Цвет кнопки - может принимать значения: white, blue, green, red
# Отправка клавиатуры:
```php
$id // ID пользователя, кому будет отправлена клавиатура, или peer_id беседы
$message // Сообщение, отправляемое вместе с клавиатурой
$buttons = [[$button, ...], ...] // Массив из отправляемый кнопок
$one_time // Не обязательный параметр. Принимает значение True или False. Если True - после нажатия клавиши клавиатуры, клавиатура исчезнет, Flase - не исчезнет. По умолчанию = False
$vk->sendButton($id, $message, $buttons, $one_time);
```
# Пример, отправка клавиатуры с текстом "Клавиатура":
```php
$id // ID пользователя, кому будет отправлена клавиатура
[[$button, ...], ...] // Массив из отправляемый кнопок
$one_time // Не обязательный параметр. Принимает значение True или False. Если True - после нажатия клавиши клавиатуры, клавиатура исчезнет, Flase - не исчезнет. По умолчанию = False
$vk->sendButton($id, 'Клавиатура', [
	[$button1_1, $button1_2],
	[$button2_1, $button2_2]
], $one_time);
```
Кнопки будут выглядеть так:
```
[ white ] [  blue ]
[ green ] [  red  ]
```
Такой запрос:
```php
$vk->sendButton($id, 'Клавиатура', [
	[$button1_1, $button1_2, $button2_2],
	[$button2_1]
]);
```
Выведет следующие кнопки:
```
[ white ] [  blue ] [  red  ]
[           green           ]
```
# Удаление кнопок (клавиатуры из диалога):
Обращаем ваше внимание, что если передать параметр $one_time = True (см. отправка клавиатуры), клавиатура исчезнет после нажатия на одну из кнопок.\
Для того, что-бы вручную выключить клавиатуру, нужно выполнить следующий запрос:
```php
$id // ID пользователя
$message // Сообщение, отправляемое при удалении клавиатуры
$vk->sendButton($id, $message);
```

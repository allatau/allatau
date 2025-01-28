<?php

namespace App\Enums;

enum ComputingResourceStatus:string {

    # Ресурс создан, но не активирован
    case DRAFT = 'DRAFT';

    # Ресурс доступен
    case ONLINE = 'ONLINE';

    # Ресурс недоступен
    case OFFLINE = 'OFFLINE';

    # Ошибка при подключении к ресурсу
    case FAILED = 'FAILED';

    # Подключение к ресурсу
    case CONNECTING = 'CONNECTING';

    # Статус ресурса неизвестен
    case UNKNOWN = 'UNKNOWN';

    # Ресурс удален
    case DELETED = 'DELETED';

}

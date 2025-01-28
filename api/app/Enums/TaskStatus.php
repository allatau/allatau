<?php

namespace App\Enums;

enum TaskStatus:string {

    # Задача создана, но не отправлена на выполнение
    case DRAFT = 'DRAFT';

    # Задача в процессе подготовки к выполнению
    case  CREATING  = 'CREATING';

    # Задача в очереди на выполнение
    case QUEUED  = 'QUEUED';

    # Задача на выполнении
    case RUNNING  = 'RUNNING';

    # Задача в процессе отмены
    case ABORTING  = 'ABORTING';

    # Задача отменена
    case ABORTED  = 'ABORTED';

    # Задача столкнулась с проблемой во время выполнения и не была успешно завершена
    case FAILED  = 'FAILED';

    # Задача успешно завершена
    case COMPLETED  = 'COMPLETED';

    # Статус задачи неизвестен
    case UNKNOWN  = 'UNKNOWN';

    # Задача удалена
    case DELETED  = 'DELETED';
}

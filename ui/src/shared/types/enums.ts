export enum ExporterEnum {
  "export_processors.xml_default_exporter" = "xml",
  "export_processors.csv_default_exporter" = "csv",
  "export_processors.csv_analysis_exporter" = "analysis.csv",
}
export type ExporterEnumKey = keyof typeof ExporterEnum;

export enum TaskStatus {
  // Задача создана, но не отправлена на выполнение
  DRAFT = "DRAFT",

  // Задача в процессе подготовки к выполнению
  CREATING = "CREATING",

  // Задача в очереди на выполнение
  QUEUED = "QUEUED",

  // Задача на выполнении
  RUNNING = "RUNNING",

  // Задача в процессе отмены
  ABORTING = "ABORTING",

  // Задача отменена
  ABORTED = "ABORTED",

  // Задача столкнулась с проблемой во время выполнения и не была успешно завершена
  FAILED = "FAILED",

  // Задача успешно завершена
  COMPLETED = "COMPLETED",

  // Статус задачи неизвестен
  UNKNOWN = "UNKNOWN",

  // Задача удалена
  DELETED = "DELETED",
}

export enum TaskStatusDescription {
  DRAFT = "Задача создана, но не отправлена на выполнение",

  CREATING = "Задача в процессе подготовки к выполнению",

  QUEUED = "Задача в очереди на выполнение",

  RUNNING = "Задача на выполнении",

  ABORTING = "Задача в процессе отмены",

  ABORTED = "Задача отменена",

  FAILED = "Задача столкнулась с проблемой во время выполнения и не была успешно завершена",

  COMPLETED = "Задача успешно завершена",

  UNKNOWN = "Статус задачи неизвестен",

  DELETED = "Задача удалена",
}

export enum ComputingResourceStatus {
  // Ресурс создан, но не активирован
  DRAFT = "DRAFT",

  // Ресурс доступен
  ONLINE = "ONLINE",

  // Ресурс недоступен
  OFFLINE = "OFFLINE",

  // Ошибка при подключении к ресурсу
  FAILED = "FAILED",

  // Подключение к ресурсу
  CONNECTING = "CONNECTING",

  // Статус ресурса неизвестен
  UNKNOWN = "UNKNOWN",

  // Ресурс удален
  DELETED = "DELETED",
}

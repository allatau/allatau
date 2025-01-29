<?php

namespace App\GraphQL\Mutations;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\CalculationCase;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as FileSystem;
use Illuminate\Support\Str;

class CreateTask
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $task = new Task();
        $task->name = $args['name'];
        $task->status = TaskStatus::DRAFT->name;
        $task->files = "{}";
        $task->script = $args['script'] ?? "";
        $task->jobs = "[]";
        $task->computing_resource_id = $args['computing_resource_id'];
        $task->project_id = $args['project_id'];
        $task->extra = "{}";
        $task->type = "default";
        $task->numerical_model = $args['numerical_model'] ?? "";
        $task->converter_service = $args['converter_service'] ?? "";
        $task->meta_values = $args['meta_values'] ?? "{}";

        // Если есть расчетный кейс и метаданные
        if (isset($args['calculation_case_id']) && !empty($args['meta_values'])) {
            $calculationCase = CalculationCase::find($args['calculation_case_id']);
            if ($calculationCase && $calculationCase->file) {
                $task->calculation_case_id = $args['calculation_case_id'];
                
                // Генерируем новый расчетный кейс и получаем информацию о нем
                $newCaseInfo = $this->generateNewCase(
                    $calculationCase->file->path,
                    json_decode($args['meta_values'], true),
                    json_decode($calculationCase->meta, true),
                    $task->name
                );
                
                // Создаем новую запись в таблице files
                $file = new File();
                $file->name = $newCaseInfo['filename'];
                $file->path = $newCaseInfo['filepath'];
                $file->save();

                // Создаем новый CalculationCase на основе сгенерированного файла
                $newCalculationCase = new CalculationCase();
                $newCalculationCase->name = $task->name . " (generated)";
                $newCalculationCase->file_id = $file->id;
                $newCalculationCase->meta = $calculationCase->meta;
                $newCalculationCase->save();

                // Обновляем задачу, связывая её с новым расчетным кейсом
                $config = config('app.url');
                $task->computational_model_resource = "{$config}/public/files/{$file->id}"; // Формируем URL для доступа к файлу
                $task->calculation_case_id = $newCalculationCase->id;
            }
        }

        $task->save();

        return $task;
    }

    private function generateNewCase($originalCasePath, $metaValues, $metaFields, $taskName)
    {
        // Создаем временную директорию в storage/app/temp
        $tempDirName = 'temp/' . uniqid();
        Storage::makeDirectory($tempDirName);
        $tempDir = Storage::path($tempDirName);

        try {
            // Получаем полный путь к исходному файлу
            $originalFullPath = Storage::path($originalCasePath);
            
            // Распаковываем архив
            $command = sprintf(
                'cd %s && tar -xzf %s',
                escapeshellarg($tempDir),
                escapeshellarg($originalFullPath)
            );
            $output = shell_exec($command);

            // Применяем изменения на основе метаданных
            foreach ($metaFields as $field) {
                if (isset($metaValues[$field['filepath']])) {
                    $filePath = $tempDir . '/' . $field['filepath'];
                    if (file_exists($filePath)) {
                        $content = file_get_contents($filePath);
                        
                        if (isset($field['pos'])) {
                            // Разбираем позицию в формате "строка:колонка"
                            list($line, $column) = array_map('intval', explode(':', $field['pos']));
                            
                            // Читаем файл построчно
                            $lines = explode("\n", $content);
                            
                            if (isset($lines[$line - 1])) {
                                $currentLine = $lines[$line - 1];
                                $newValue = $metaValues[$field['filepath']];
                                
                                // Заменяем значение в указанной позиции
                                $lines[$line - 1] = substr_replace(
                                    $currentLine,
                                    $newValue,
                                    $column - 1,
                                    $field['length']
                                );
                                
                                // Собираем файл обратно
                                $content = implode("\n", $lines);
                                file_put_contents($filePath, $content);
                            }
                        }
                    }
                }
            }

            // Создаем новый архив
            $newArchiveName = Str::slug($taskName) . '-' . uniqid() . '.tar.gz';
            $relativePath = 'public/cases/' . $newArchiveName;
            
            // Создаем директорию для cases, если её нет
            if (!file_exists(storage_path('app/public/cases'))) {
                mkdir(storage_path('app/public/cases'), 0755, true);
            }
            
            // Создаем архив напрямую в нужной директории
            $absolutePath = storage_path('app/' . $relativePath);
            $command = sprintf(
                'cd %s && tar -czf %s .',
                escapeshellarg($tempDir),
                escapeshellarg($absolutePath)
            );
            $output = shell_exec($command);

            if (!file_exists($absolutePath)) {
                throw new \Exception('Failed to create archive');
            }

            // Возвращаем информацию о новом файле с абсолютным путем
            return [
                'filename' => $newArchiveName,
                'filepath' => $absolutePath, // Абсолютный путь для сохранения в БД
                'url' => $relativePath // Относительный путь для Storage
            ];
        } finally {
            // Очищаем временную директорию
            Storage::deleteDirectory($tempDirName);
        }
    }
}

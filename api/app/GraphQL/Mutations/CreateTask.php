<?php

namespace App\GraphQL\Mutations;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\CalculationCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
        $task->computational_model_resource = $args['computational_model_resource'] ?? "";
        $task->extra = "{}";
        $task->type = "default";
        $task->numerical_model = $args['numerical_model'] ?? "";
        $task->converter_service = $args['converter_service'] ?? "";

        // Обработка расчетного кейса и метаданных
        if (isset($args['calculation_case_id'])) {
            // Получаем исходный расчетный кейс
            $calculationCase = CalculationCase::find($args['calculation_case_id']);
            if ($calculationCase) {
                $task->calculation_case_id = $args['calculation_case_id'];
                
                if (isset($args['meta_values'])) {
                    $task->meta_values = $args['meta_values'];
                    
                    if ($calculationCase->file) {
                        // Генерируем новый расчетный кейс на основе метаданных
                        $newCaseUrl = $this->generateNewCase(
                            $calculationCase->file->path, 
                            json_decode($args['meta_values'], true), 
                            json_decode($calculationCase->meta, true)
                        );
                        $task->computational_model_resource = $newCaseUrl;
                    }
                }
            }
        }

        $task->save();

        return $task;
    }

    private function generateNewCase($originalCasePath, $metaValues, $metaFields)
    {
        // Создаем временную директорию
        $tempDir = storage_path('app/temp/' . uniqid());
        mkdir($tempDir, 0755, true);

        // Распаковываем архив
        $output = shell_exec("cd {$tempDir} && tar -xzf {$originalCasePath}");

        // Применяем изменения на основе метаданных
        foreach ($metaFields as $field) {
            if (isset($metaValues[$field['filepath']])) {
                $filePath = $tempDir . '/' . $field['filepath'];
                if (file_exists($filePath)) {
                    $content = file_get_contents($filePath);
                    // Здесь нужно реализовать логику замены значений
                    // в зависимости от формата метаданных
                    file_put_contents($filePath, $content);
                }
            }
        }

        // Создаем новый архив
        $newArchiveName = uniqid() . '.tar.gz';
        $newArchivePath = storage_path('app/public/cases/' . $newArchiveName);
        $output = shell_exec("cd {$tempDir} && tar -czf {$newArchivePath} .");

        // Очищаем временную директорию
        File::deleteDirectory($tempDir);

        // Возвращаем URL нового архива
        return '/storage/cases/' . $newArchiveName;
    }
}

<?php

namespace App\Models\Concerns;

use App\Enums\ActivityAction;
use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Automatically writes an activity log entry whenever the model is created, updated, deleted or restored.
 */
trait RecordsActivity
{
    /**
     * Attributes that must never be written to the activity log.
     *
     * @var list<string>
     */
    protected static array $hiddenFromActivityLog = ['remember_token', 'updated_at', 'created_at', 'deleted_at'];

    /**
     * Attributes whose change is logged, but whose value is masked.
     *
     * @var list<string>
     */
    protected static array $maskedInActivityLog = ['password'];

    public static function bootRecordsActivity(): void
    {
        static::created(function (self $model): void {
            $model->recordActivity(ActivityAction::Created, $model->loggableAttributes($model->getAttributes()));
        });

        static::updated(function (self $model): void {
            $changes = $model->loggableAttributes($model->getChanges());

            if ($changes === []) {
                return;
            }

            $old = $model->loggableAttributes(array_intersect_key($model->getPrevious(), $changes));

            $model->recordActivity(ActivityAction::Updated, ['old' => $old, 'new' => $changes]);
        });

        static::deleted(function (self $model): void {
            $isPermanent = in_array(SoftDeletes::class, class_uses_recursive($model), true) && $model->isForceDeleting();

            $model->recordActivity(
                $isPermanent ? ActivityAction::ForceDeleted : ActivityAction::Deleted,
                $model->loggableAttributes($model->getAttributes()),
            );
        });

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            static::restored(function (self $model): void {
                $model->recordActivity(ActivityAction::Restored, []);
            });
        }
    }

    /**
     * Human readable name of the model type, e.g. "produk".
     */
    abstract public function activitySubjectLabel(): string;

    /**
     * Human readable title of this record, e.g. the product name.
     */
    abstract public function activitySubjectTitle(): string;

    /**
     * @param  array<string, mixed>  $properties
     */
    protected function recordActivity(ActivityAction $action, array $properties): void
    {
        $verb = match ($action) {
            ActivityAction::Created => 'Menambahkan',
            ActivityAction::Updated => 'Mengubah',
            ActivityAction::Deleted => 'Menghapus',
            ActivityAction::Restored => 'Memulihkan',
            ActivityAction::ForceDeleted => 'Menghapus permanen',
            default => $action->label(),
        };

        app(ActivityLogger::class)->log(
            $action,
            sprintf('%s %s "%s"', $verb, $this->activitySubjectLabel(), $this->activitySubjectTitle()),
            $this,
            $properties,
        );
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected function loggableAttributes(array $attributes): array
    {
        $attributes = array_diff_key($attributes, array_flip(static::$hiddenFromActivityLog));

        foreach ($attributes as $key => $value) {
            if (in_array($key, static::$maskedInActivityLog, true)) {
                $attributes[$key] = '********';
            } elseif (is_string($value) && mb_strlen($value) > 500) {
                $attributes[$key] = mb_substr($value, 0, 500).'…';
            }
        }

        return $attributes;
    }
}

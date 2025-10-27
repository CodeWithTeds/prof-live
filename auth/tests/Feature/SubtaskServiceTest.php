<?php

use App\Models\Task;
use App\Services\Task\SubtaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('adds a subtask and returns children for parent', function () {
    $parent = Task::factory()->create();
    $child = Task::factory()->create();

    $service = app(SubtaskService::class);
    $ok = $service->addSubtask($parent, $child);

    expect($ok)->toBeTrue();

    $child->refresh();
    expect($child->parent_id)->toBe($parent->id);

    $subs = $service->getSubtasks($parent);
    expect($subs->pluck('id')->all())->toContain($child->id);
});

it('prevents cycle when trying to make parent a child of its own descendant', function () {
    $a = Task::factory()->create();
    $b = Task::factory()->create();

    $service = app(SubtaskService::class);

    // A -> B
    $ok1 = $service->addSubtask($a, $b);
    expect($ok1)->toBeTrue();

    // Try to create cycle: B -> A (should fail)
    $ok2 = $service->addSubtask($b, $a);
    expect($ok2)->toBeFalse();

    $a->refresh();
    expect($a->parent_id)->toBeNull();
});

it('removes a subtask correctly', function () {
    $parent = Task::factory()->create();
    $child = Task::factory()->create();

    $service = app(SubtaskService::class);
    $service->addSubtask($parent, $child);

    // Refresh the child to pick up DB changes made inside the service
    $child->refresh();

    $service->removeSubtask($parent, $child);

    $child->refresh();
    expect($child->parent_id)->toBeNull();
});
<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(Tests\TestCase::class, RefreshDatabase::class);

// G-7: Category::getDescendantIds()
beforeEach(function () {
    // Reset the static map between tests
    $ref = new ReflectionProperty(Category::class, 'categoryParentMap');
    $ref->setAccessible(true);
    $ref->setValue(null, null);

    Cache::flush();
});

function makeCategory(string $name, ?int $parentId = null): Category
{
    return Category::create([
        'category_name'  => $name,
        'status'         => 'active',
        'category_image' => 'placeholder.jpg',
        'parent_id'      => $parentId,
    ]);
}

test('single category with no children returns only itself', function () {
    $cat = makeCategory('Top');

    expect($cat->getDescendantIds())->toBe([$cat->id]);
});

test('returns parent and all direct children', function () {
    $parent = makeCategory('Parent');
    $child1 = makeCategory('Child 1', $parent->id);
    $child2 = makeCategory('Child 2', $parent->id);

    $ids = $parent->getDescendantIds();

    expect($ids)->toContain($parent->id)
        ->toContain($child1->id)
        ->toContain($child2->id)
        ->toHaveCount(3);
});

test('traverses three levels deep', function () {
    $grandparent = makeCategory('Grandparent');
    $parent      = makeCategory('Parent', $grandparent->id);
    $child       = makeCategory('Child', $parent->id);

    $ids = $grandparent->getDescendantIds();

    expect($ids)->toContain($grandparent->id)
        ->toContain($parent->id)
        ->toContain($child->id)
        ->toHaveCount(3);
});

test('sibling categories are not included in each others descendants', function () {
    $root    = makeCategory('Root');
    $branch1 = makeCategory('Branch 1', $root->id);
    $branch2 = makeCategory('Branch 2', $root->id);
    $leaf    = makeCategory('Leaf', $branch1->id);

    $ids = $branch2->getDescendantIds();

    expect($ids)->toContain($branch2->id)
        ->not->toContain($leaf->id)
        ->not->toContain($branch1->id)
        ->toHaveCount(1);
});

test('depth guard prevents infinite loop on circular parent reference', function () {
    // Create two categories, then directly patch the DB to create a cycle
    $catA = makeCategory('A');
    $catB = makeCategory('B', $catA->id);

    // Force circular: B's child points back to A
    Category::withoutTimestamps(fn () =>
        \Illuminate\Support\Facades\DB::table('categories')->where('id', $catA->id)->update(['parent_id' => $catB->id])
    );

    // Reset map so it re-fetches the circular data
    $ref = new ReflectionProperty(Category::class, 'categoryParentMap');
    $ref->setAccessible(true);
    $ref->setValue(null, null);

    // Should terminate without hanging
    $ids = $catA->fresh()->getDescendantIds();
    expect($ids)->toBeArray();
});

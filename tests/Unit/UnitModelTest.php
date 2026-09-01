<?php

namespace Tests\Unit;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class UnitModelTest extends TestCase
{
    public function test_unit_has_divisis_relationship(): void
    {
        $unit = new Unit();

        $this->assertTrue(method_exists($unit, 'divisis'));
        $this->assertInstanceOf(BelongsTo::class, $unit->divisis());
    }
}

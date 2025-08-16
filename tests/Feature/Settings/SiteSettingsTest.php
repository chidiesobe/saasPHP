<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\Setting;
use App\Filament\Pages\SiteSettings;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\Test;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::truncate();

        // Create user with required permission
        $this->user = User::factory()->create();

        // Allow all gates during test
        Gate::before(fn() => true);

        $this->actingAs($this->user);
    }

    #[Test]
    public function mount_populates_form_with_existing_settings()
    {
        Setting::create([
            'key'   => 'site.name',
            'value' => 'Acme Corp',
            'type'  => 'string',
            'group' => 'site',
        ]);
        Setting::create([
            'key'   => 'site.theme',
            'value' => 'light',
            'type'  => 'string',
            'group' => 'site',
        ]);

        Livewire::test(SiteSettings::class)
            ->assertFormSet([
                'data.site.name'  => 'Acme Corp',
                'data.site.theme' => 'light',
            ]);
    }

    #[Test]
    public function validation_fails_if_required_fields_are_missing()
    {
        Livewire::test(SiteSettings::class)
            // we don’t set logo or the required selects
            ->call('submit')
            ->assertHasErrors([
                'data.site.logo'        => 'required',
                'data.site.timezone'    => 'required',
                'data.site.date_format' => 'required',
                'data.site.language'    => 'required',
            ]);
    }

    #[Test]
    public function file_upload_logo_is_required_if_not_already_set()
    {
        // no logo seeded in DB
        Livewire::test(SiteSettings::class)
            ->call('submit')
            ->assertHasErrors(['data.site.logo' => 'required']);
    }
}

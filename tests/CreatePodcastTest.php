<?php
namespace Podhost\Podstream\Tests;

use App\Actions\Podstream\CreatePodcast;
use App\Models\Podcast;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Podhost\Podstream\Podstream;
use Podhost\Podstream\Tests\Fixtures\PodcastPolicy;
use Podhost\Podstream\Tests\Fixtures\User;

class CreatePodcastTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Podcast::class, PodcastPolicy::class);
        Podstream::useUserModel(User::class);
    }

    protected function migrate()
    {
        // $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

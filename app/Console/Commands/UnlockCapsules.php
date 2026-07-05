<?php
// app/Console/Commands/UnlockCapsules.php
namespace App\Console\Commands;

use App\Models\Capsule;
use Illuminate\Console\Command;

class UnlockCapsules extends Command
{
    protected $signature = 'capsules:unlock';
    protected $description = 'Unlock capsules whose unlock_date has passed';

    public function handle()
    {
        $count = Capsule::where('is_locked', true)
            ->where('unlock_date', '<=', now())
            ->update(['is_locked' => false, 'status' => 'unlocked']);

        $this->info("Unlocked {$count} capsule(s).");
    }
}
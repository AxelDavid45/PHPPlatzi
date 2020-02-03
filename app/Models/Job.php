<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDefaultImage;

class Job extends Model {
    protected $table = 'jobs';

    use HasDefaultImage;

    public function getDurationAsString() {
        $years = floor($this->months / 12);
        $extraMonths = $this->months % 12;
      
        return "Job duration: $years years $extraMonths months";
    }
}
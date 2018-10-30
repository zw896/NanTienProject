<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class PreModelDelete {
    use SerializesModels;

    protected $model;

    /**
     * PreModelDelete constructor.
     * @param Model $model
     */
    public function __construct(Model $model) {
        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel() {
        return $this->model;
    }
}

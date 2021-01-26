<?php
namespace App\Service;

use App\Models\Historique as HModel;
use App\Models\User;

class Historique {

    /**
     * Enregistrement historique
     * @param string $modelId
     * @param string $model
     * @param string $action
     * @param User $userId;
     */
    public static function save(string $modelId, string $model, string $action, User $user)
    {
        $m = new HModel();
        $m->modelId = $modelId;
        $m->model = $model;
        $m->action = $action;
        $m->user()->associate($user);
        $m->save();
    }

}
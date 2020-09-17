<?php
namespace Mehnat\User\Services;

use Mehnat\User\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Mehnat\User\Entities\User;

class UserService
{
    private $userRepo;
    private $users;

    public function __construct()
    {
        $this->userRepo = new UserRepository;
    }
    public function filter(Builder $query): Builder
    {
        $user_name = request()->get('username', false);
        $age = request()->get('age', false);
        $status = request()->get('status', false);

        if ($user_name){
            $query->where('username', 'like', "%$user_name%");
        }

        if ($age){
            $query->where('age', '=', $age);
        }

        if ($status){
            switch ($status) {
                case 1:
                    $query->active();
                    break;
                case 2:
                    $query->disabled();
                    break;
                case 0:
                    $query->bunned();
                    break;
                default:
                    break;
            }
        }
        
        return $query;
    }

    public function sort($query): Builder
    {
        $key = request()->get('sort_key','username');
        $order = request()->get('sort_order', 'asc');
        $query->orderBy($key, $order);

        return $query;
    }
    public function notify($user, string $type)
    {
        $strategy = (new NotificationStrategy)->getStrategy($type);
        $strategy->send();
    }
    public function getUsers(): Collection
    {
        $users = $this->userRepo->getQuery();
        $users = $this->filter($users);
        $users = $this->sort($users);
        $users = $this->userRepo->get($users);
        return $users;
    }
}
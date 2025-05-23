<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Arr;
use OpenAdmin\Admin\Auth\Database\Administrator;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user()
    {
        // Επιστρέψτε τον χρήστη του OpenAdmin
        return Administrator::find($this->id);
    }

    public function links()
    {
        return $this->hasMany("App\Link");
    }

    public function projects()
    {
        return $this->hasMany("App\Project");
    }

    public function files()
    {
        return $this->hasMany("App\File");
    }

    public function votes()
    {
        return $this->hasMany("App\Vote");
    }

    public function comments()
    {
        return $this->hasMany("App\Comment");
    }

    public function social()
    {
        return $this->hasMany("App\SocialAccount");
    }

    public function userGraphs()
    {
        return $this->ownGraphs()->merge($this->publicGraphs());
    }

    public function ownGraphs()
    {
        return File::where('user_id', $this->id)->withCount('projects')->with('projects')->get();
    }

    public function publicGraphs()
    {
        return File::where('public', true)->where('user_id', '!=', $this->id)->withCount('projects')->with('projects')->get();
    }

    public function userAccessibleProjects()
    {
        $projects = Project::where('user_id', '=', $this->id)
                ->orWhere('public', '=', true)
                ->get();

        return $projects;
    }

    public function userAccessibleProjectsArray()
    {
        $projects = $this->userAccessibleProjects();
        $select = [];
        foreach ($projects as $project) {
            $select = Arr::add($select, $project->id, $project->name);
        }

        return $select;
    }

}

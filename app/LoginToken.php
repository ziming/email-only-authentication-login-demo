<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{

    /**
     * Fillable fields for the model.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'token'];

    /**
     * Generate a new token for the given user.
     *
     * @param  User $user
     * @return $this
     */
    public static function generateFor(User $user)
    {
        return static::create([
            'user_id' => $user->id,
            'token'   => str_random(50)
        ]);
    }

        /**
     * Send the token to the user.
     */
    public function send()
    {
        // jeff said it used to felt wrong to send email in models
        // but overtime he don't care anymore. It never gave him problem doing it like this so far
        // food for thought
        $url = url('/auth/token', $this->token);
        Mail::raw(
            "<a href='{$url}'>{$url}</a>",
            function ($message) {
                $message->to($this->user->email)
                        ->subject('Login to Laracasts');
            }
        );
    }

    /**
     * A token belongs to a registered user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the route key for implicit model binding. Currently for AuthController@authenticate method for the LoginToken
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'token'; // static::where('token', $wildcard); instead of static::where('id', $wildcard) which is the default
    }
}

<?php

namespace App\Policies;

use App\User;
use App\ThreadReply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the thread reply.
     *
     * @param  \App\User  $user
     * @param  \App\ThreadReply  $threadReply
     * @return mixed
     */
    public function view(User $user, ThreadReply $threadReply)
    {
        //
    }

    /**
     * Determine whether the user can create thread replies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the thread reply.
     *
     * @param  \App\User  $user
     * @param  \App\ThreadReply  $threadReply
     * @return mixed
     */
    public function update(User $user, ThreadReply $threadReply)
    {
        return $user->id == $threadReply->user_id && $threadReply->deleted==0;
    }

    /**
     * Determine whether the user can delete the thread reply.
     *
     * @param  \App\User  $user
     * @param  \App\ThreadReply  $threadReply
     * @return mixed
     */
    public function delete(User $user, ThreadReply $threadReply)
    {
        //
    }

    /**
     * Determine whether the user can restore the thread reply.
     *
     * @param  \App\User  $user
     * @param  \App\ThreadReply  $threadReply
     * @return mixed
     */
    public function restore(User $user, ThreadReply $threadReply)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the thread reply.
     *
     * @param  \App\User  $user
     * @param  \App\ThreadReply  $threadReply
     * @return mixed
     */
    public function forceDelete(User $user, ThreadReply $threadReply)
    {
        //
    }

	public function favourite(User $user, ThreadReply $reply) {
		return auth()->check();
	}
}

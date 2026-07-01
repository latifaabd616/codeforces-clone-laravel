<?php

namespace App\Http\Resources;
use App\Http\Resources\UserResource; // تأكد من استيراد الكلاس
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
   
    public function toArray($request) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'level' => new LevelResource($this->whenLoaded('level')),
            'avatar' => $this->avatar,
            'total_xp' => $this->TotalXP,
            'created_at' => $this->created_at,
        ];
    }
////////////////////////////////////////////////////////////////////////////////////////////////
    
/* public function index()
{
    $users = User::paginate(10);
    return UserResource::collection($users); // للمجموعات
} */


public function index() {
    $users = User::with('level')->paginate(10);
    return UserResource::collection($users);
}
//////////////////////////////////////////////////////////////////////////////////////


public function store(Request $request) {
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'level_id' => 'required|exists:level,id',
        'avatar' => 'sometimes|image|max:2048'
    ]);

    if ($request->hasFile('avatar')) {
        $validated['avatar'] = $request->file('avatar')->store('avatars');
    }

    $user = User::create($validated);
    return new UserResource($user->load('level'));
}
////////////////////////////////////////////////////////////////////////////////////////

public function update(Request $request, User $user) {
    $validated = $request->validate([
        'name' => 'sometimes|string',
        'email' => 'sometimes|email|unique:users,email,'.$user->id,
        'level_id' => 'sometimes|exists:level,id',
        'avatar' => 'sometimes|image|max:2048'
    ]);

    if ($request->hasFile('avatar')) {
        Storage::delete($user->avatar);
        $validated['avatar'] = $request->file('avatar')->store('avatars');
    }

    $user->update($validated);
    return new UserResource($user->load('level'));
}
////////////////////////////////////////////////////////////////////////////////////////
public function show(User $user)
{
    return new UserResource($user->load('level'));
}
}

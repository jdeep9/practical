<?php

namespace App\Http\Controllers;

use App\Hobby;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
// use Illuminate\Foundation\Auth\User;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    const ITEM_PER_PAGE = 10;
    public $user;
    public $hobby;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(User $user, Hobby $hobby)
    {
        $this->user = $user;
        $this->hobby = $hobby;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status_code = 200;
        $message = "success";

        try{
            $searchParams = $request->all();
            $userQuery = $this->user->query();
            $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
            $data = $userQuery->paginate($limit);
            if(!empty($data)){
                return response()->json(['status_code' => $status_code, 'message' => $message, 'data' => $data]);
            }else{
                return response()->json(['status_code' => 400, 'message' => 'failed']);
            }
        } catch(Exception $ex) {
            return response()->json(['status_code' => 400, 'message' => $ex->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        // dd( $id);
        $status_code = 200;
        $message = "successfully update the user";

        DB::beginTransaction();
        try {
            $data = $request->all();

            $data['image'] = $request->file('image')->getClientOriginalName();

            $path = $request->file('image')->store('public/images');

            $user = $this->user->updateUser($data, $id);
            // dd($user);
            if($user == 1){
                DB::commit();
                return response()->json(['status_code' => $status_code, 'message' => $message]);
            }else{
                return response()->json(['status_code' => 400, 'message' => 'failed']);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status_code' => 400, 'message' => $ex->getMessage()]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status_code = 200;
        $message = "successfully delete the user";

        DB::beginTransaction();
        try {
            $data = $this->user->deleteUser($id);
            if($data == 1){
                DB::commit();
                return response()->json(['status_code' => $status_code, 'message' => $message]);
            }else{
                return response()->json(['status_code' => 400, 'message' => 'failed']);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status_code' => 400, 'message' => $ex->getMessage()]);

        }
    }

    /**
     * Add the specified Hobby to the user from storage.
     *
     *@param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hobby(Request $request)
    {
        $status_code = 200;
        $message = "successfully updated the hobby";

        $this->validate($request, [
            'hobby_name' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $data = $request->all();

            $userId = auth()->user()->id;

            $hobby = Hobby::create([
                'user_id'  => $userId,
                'name' => $data['hobby_name']
            ]);

            if(!empty($hobby)){
                DB::commit();
                return response()->json(['status_code' => $status_code, 'message' => $message, 'data' => $hobby]);
            }else{
                return response()->json(['status_code' => 400, 'message' => 'failed']);
            }

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status_code' => 400, 'message' => $ex->getMessage()]);
        }
    }

    /**
     * Super admin can filter user listing by hobby.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $status_code = 200;
        $message = "success";

        $this->validate($request, [
            'hobby_name' => 'required',
        ]);

        try{
            $data = $request->all();

            if(auth()->user()->hasRole('Super Admin')){
                $users = $this->hobby->getUserListByHobby($data);
                if(!empty($users)){
                    DB::commit();
                    return response()->json(['status_code' => $status_code, 'message' => $message, 'data' => $users]);
                }else{
                    return response()->json(['status_code' => 400, 'message' => 'failed']);
                }
            }else{
                return response()->json(['status_code' => 400, 'message' => 'Only super admin has access to filter user listing by hobby.']);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status_code' => 400, 'message' => $ex->getMessage()]);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


}

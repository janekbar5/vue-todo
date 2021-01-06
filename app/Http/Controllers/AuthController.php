<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;

use Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
//
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
    use ResetsPasswords, SendsPasswordResetEmails {
        ResetsPasswords::broker insteadof SendsPasswordResetEmails;
    }
    

    public function __construct()
    {
        //$this->middleware(['auth','verified']);
    }
    
    
    
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();        
        
        $user->sendEmailVerificationNotification();
        
        return response()->json(['status' => 'success'], 200);
    }

   
    public function login(Request $request)
    {
       
        $credentials = $request->only('email', 'password');
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }else if(Auth::user()->hasVerifiedEmail() == 'false'){           
                return response()->json(['status' => 'success', 'token' => $token], 200)->header('Authorization', $token);
            }else{
                //echo 'not verified';
                return response()->json(['status' => 'error'], 401);

            }            
        } catch (JWTException $e) {
            
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

    }
    
    public function verify(Request $request)
    {
        
        if ($request->hasValidSignature()) {
            //abort(401);
            $user = User::find($request->input('id'));
            $user->markEmailAsVerified();
        return redirect('login')->with('verified', true);
       }
    }
   
    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

   
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    
    private function guard()
    {
        return Auth::guard();
    }

   
    public function sendPasswordResetLink(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

   
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Password reset email sent.',
            'data' => $response
        ]);
    }

  
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Email could not be sent to this email address.']);
    }

   
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

   
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        event(new PasswordReset($user));
    }

   
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset successfully.']);
    }

   
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Failed, Invalid Token.']);
    }
}

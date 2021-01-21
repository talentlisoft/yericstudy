<?php

namespace App\Http\Controllers\Trainee;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class loginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainee')->only('logout');
        $this->middleware('traineeguest')->only('showloginpage');
    }

    public function showloginpage()
    {
        return view('auth.traineelogin');
    }

    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'traineename' => 'required',
                'password'    => 'required',
                'captcha'     => 'required|captcha',
            ], [
                'captcha.captcha' => '验证码不匹配'
            ]);

            if ($this->attemptLogin($request)) {
                return redirect('/trainee/mytrain/mytrains/list');
            } else {
                return redirect('/')
                    ->withErrors(['password' => '用户名与密码不匹配']);
            }
//            $traineeRecord = DB::table('trainees')
//                ->select('id', 'password', 'avatar', 'name')
//                ->where('name', $request->input('traineename'))
//                ->first();
//            if ($traineeRecord) {
//                if (Hash::check($request->input('password'), $traineeRecord->password)) {
//                    $request->session()->put(['logined_trainee' => [
//                        'trainee_id' => $traineeRecord->id,
//                        'name'       => $traineeRecord->name,
//                        'avatar'     => $traineeRecord->avatar
//                    ]]);
//                    //Goto trainee main page
//                    return redirect('/trainee/mytrain/mytrains/list');
//                } else {
//                    return redirect('/')
//                        ->withErrors(['password' => '密码不正确'])
//                        ->withInput();
//                }
//            } else {
//                return redirect('/')
//                    ->withErrors(['traineename' => '学员不存在'])
//                    ->withInput();
//            }
        } catch (QueryException $e) {
            Log::error('loginController->login->QueryException异常' . $e->getMessage());
            abort(500);
        } catch (ValidationException $e) {
            return redirect('/')
                ->withErrors(['captcha' => '缺少用户名或者密码，或者验证码不匹配']);
        }
        catch (Exception $e) {
            Log::error('loginController->login->Exception' . $e->getMessage());
            abort(500);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('trainee')->logout();
        $request->session()->invalidate();
        return redirect('/');
    }


    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('trainee');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return ['name' => $request->input('traineename'), 'password' => $request->input('password')];
    }
}

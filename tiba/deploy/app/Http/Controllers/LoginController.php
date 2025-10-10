<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Request;
use Validator;
use Auth;
use Redirect;
use View;
use Session;

class LoginController extends Controller
{
 function index()
    {
     return view('checklogin');
    }
  function doLogin(Request $request){
      //Validate requests
      // validate the info, create rules for the inputs
    $rules = array(
        'email'    => 'required|email', // make sure the email is an actual email
        'password' => 'required|alphaNum|min:3', // password can only be alphanumeric and has to be greater than 3 characters
        'invalid'=>'Enter valid credentials'
    );

    // run the validation rules on the inputs from the form
    $validator = Validator::make($request::all(), $rules);

    // if the validator fails, redirect back to the form
    if ($validator->fails()) {
        return Redirect::to('login')
                    ->withErrors($validator) // send back all errors to the login form
                    ->withInput($request::except('password')); // send back the input (not the password) so that we can repopulate the form
          
    }else{
        $userInfo = User::where('email','=',  $request::get('email'))->first();

        if(!$userInfo){
            return back()->with('fail','We do not recognize your email address');
        }else{
            //check password
            if(Hash::check($request::get('password'), $userInfo->password)){
                Session::put('LoggedUser', $userInfo->email);
                return redirect('index');
     
            }else{
                return back()->with('fail','Incorrect password');
            }
        }

    }

 
  }
//   function doLogin(Request $request)
//    {
//     // validate the info, create rules for the inputs
//     $rules = array(
//         'email'    => 'required|email', // make sure the email is an actual email
//         'password' => 'required|alphaNum|min:3', // password can only be alphanumeric and has to be greater than 3 characters
//         'invalid'=>'Enter valid credentials'
//     );

//     // run the validation rules on the inputs from the form
//     $validator = Validator::make($request::all(), $rules);

//     // if the validator fails, redirect back to the form
//     if ($validator->fails()) {
//         return Redirect::to('login')
//             ->withErrors($validator) // send back all errors to the login form
//             ->withInput($request::except('password')); // send back the input (not the password) so that we can repopulate the form
//     } else {

//         // create our user data for the authentication
//         $userdata = array(
//             'email'     => $request::get('email'),
//             'password'  => $request::get('password')
//         );

//         // attempt to do the login
//         if (Auth::attempt($userdata)) { 
//             return Redirect::to('index');

//         } else {        

//             // validation not successful, send back to form 
//             return Redirect::to('login')
//               ->with('message', 'Invalid username or password');

//         }

//      }
    // }
    public function showLogin(){
        return View::make('mlogin');
    }
    public function logout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('mlogin'); // redirect the user to the login screen
    }
    function doLogout(){
        if(session()->has('LoggedUser')){
            session()->pull('LoggedUser');
            return redirect('/login');
        }
    }
}

?>
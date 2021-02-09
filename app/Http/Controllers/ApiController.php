<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Http\Resources\LoginResource;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * @var mixed
     */
    public $base_url;

    /**
     * @var ApiService
     */
    public $apiService;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->apiService = new ApiService();
        $this->base_url = env('APP_URL');
        $this->middleware('api_access', ['except' => ['login', 'checkEmail', 'checkMobile', 'streams', 'subjects', 'postStudentRegister', 'verifyEmail','resendOtp','forgotPassword','verifyForgotPassword','verifyChangePassword','postProfessorRegister']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        $errors = json_decode($validate->errors());
        foreach ($errors as $list) {
            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $list[0],
                    'data' => array()
                ], 200);
            }
        }
        $email = $request->email;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            if ($userDetail->verify) {
                return response()->json(['success' => false, 'message' => 'Email already exists', 'data' => array()], 200);
            } else {
                return response()->json(['success' => true, 'message' => 'Email not found', 'data' => array()], 200);
            }
        } else {
            return response()->json(['success' => true, 'message' => 'Email not found', 'data' => array()], 200);

        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkMobile(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'mobile' => 'required',
        ]);
        $errors = json_decode($validate->errors());
        foreach ($errors as $list) {
            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $list[0],
                    'data' => array()
                ], 200);
            }
        }
        $mobile = $request->mobile;
        if (user()->where('mobile', $mobile)->count() > 0) {
            $userDetail = user()->where('mobile', $mobile)->first();
            if ($userDetail->verify) {
                return response()->json(['success' => false, 'message' => 'Mobile already exists', 'data' => array()], 200);
            } else {
                return response()->json(['success' => true, 'message' => 'Mobile not found', 'data' => array()], 200);
            }
        } else {
            return response()->json(['success' => true, 'message' => 'Mobile not found', 'data' => array()], 200);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function streams()
    {
        $streams = streams()->where('is_standard', 0)->get()->toArray();
        if (!empty($streams) && count($streams) > 0) {
            return response()->json(['success' => true, 'message' => 'Stream List', 'data' => $streams], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No Stream Found', 'data' => array()], 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subjects(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'stream_uuid' => 'required',
        ]);
        $errors = json_decode($validate->errors());
        foreach ($errors as $list) {
            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $list[0],
                    'data' => array()
                ], 200);
            }
        }

        $stream_uuid = $request->stream_uuid;
        $streams = subjects()->where('stream_uuid', $stream_uuid)->orderBy('title', 'ASC')->get()->toArray();
        if (!empty($streams) && count($streams) > 0) {
            return response()->json(['success' => true, 'message' => 'Subject List', 'data' => $streams], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No Subjects Found', 'data' => array()], 200);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        $errors = json_decode($validate->errors());

        foreach ($errors as $list) {
            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $list[0],
                    'data' => array()
                ], 200);
            }
        }

        $email = $request->email;
        $password = $request->password;
        $credentials = ['email' => $email, 'password' => $password];
        try {
            if(user()->where('email',$email)->count() > 0){
                $userDetail = user()->where('email',$email)->first();
                if(!in_array($userDetail->role->title,checkRoles())){
                    return response()->json(['success' => false, 'message' => trans('api.do_not_have_access'), 'data' => array()]);
                }
                $token = JWTAuth::attempt($credentials);
                if (!$token) {
                    return response()->json(['success' => false, 'message' => 'invalid_credentials', 'data' => array()], 200);
                }
                $data = user()->with('role', 'country_detail', 'state_detail', 'city_detail','student_subjects.subject','professor_subjects.subject')->where('email', $email)->first()->toArray();
                $data['token'] = $token;
                $data['image'] = $this->base_url . $data['image'];
                return response()->json(['success' => true, 'message' => 'Login Successful', 'data' => $data], 200);
            }else{
                return response()->json(['success' => false, 'message' => 'Email not found', 'data' => array()], 200);
            }
      } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'could_not_create_token', 'data' => array()], 200);
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function postStudentRegister(Request $request)
    {
        $validation = $this->apiService->studentRegisterValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],
                'data' => array()
            ], 200);
        }
        return response()->json($this->apiService->studentRegistration($request));
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function postProfessorRegister(Request $request)
    {
        $validation = $this->apiService->professorRegisterValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],
                'data' => array()
            ], 200);
        }
        return response()->json($this->apiService->professorRegistration($request));
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function verifyEmail(Request $request)
    {
        $validation = $this->apiService->verifyEmailValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],
                'data' => array()
            ], 200);
        }
        return response()->json($this->apiService->verifyEmail($request));
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function resendOtp(Request $request){
        $validation = $this->apiService->resendOtpValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],
                'data' => array()
            ], 200);
        }
        return response()->json($this->apiService->resendOtp($request));
    }

    public function forgotPassword(Request $request){
        $validation = $this->apiService->forgotPasswordValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],
                'data' => array()
            ], 200);
        }
        return response()->json($this->apiService->forgotPassword($request));
    }

    public function verifyForgotPassword(Request $request){
        $validation = $this->apiService->verifyForgotPasswordValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],
                'data' => array()
            ], 200);
        }
        return response()->json($this->apiService->verifyForgotPassword($request));
    }

    public function verifyChangePassword(Request $request){
        $validation = $this->apiService->changePasswordValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],
                'data' => array()
            ], 200);
        }
        return response()->json($this->apiService->changePassword($request));
    }

    public function moderatorPosts(Request $request){
        $validation = $this->apiService->moderatorPostsValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],
                'data' => array()
            ], 200);
        }
        return response()->json($this->apiService->moderatorPosts($request));
    }
}

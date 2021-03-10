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
        $this->middleware('api_access', ['except' => ['login', 'checkEmail', 'checkMobile', 'streams', 'subjects', 'postStudentRegister', 'verifyEmail','resendOtp','forgotPassword','verifyForgotPassword','verifyChangePassword','postProfessorRegister','countries','states','cities']]);
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

                ], 200);
            }
        }
        $email = $request->email;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            if ($userDetail->verify) {
                return response()->json(['success' => false, 'message' => 'Email already exists', ], 200);
            } else {
                return response()->json(['success' => true, 'message' => 'Email not found', ], 200);
            }
        } else {
            return response()->json(['success' => true, 'message' => 'Email not found', ], 200);

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

                ], 200);
            }
        }
        $mobile = $request->mobile;
        if (user()->where('mobile', $mobile)->count() > 0) {
            $userDetail = user()->where('mobile', $mobile)->first();
            if ($userDetail->verify) {
                return response()->json(['success' => false, 'message' => 'Mobile already exists', ], 200);
            } else {
                return response()->json(['success' => true, 'message' => 'Mobile not found', ], 200);
            }
        } else {
            return response()->json(['success' => true, 'message' => 'Mobile not found', ], 200);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries()
    {
        $countries = country()->get()->toArray();
        if (!empty($countries) && count($countries) > 0) {
            return response()->json(['success' => true, 'message' => 'Country List', 'data' => $countries], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No Country Found', ], 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function states(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'country_id' => 'required',
        ]);
        $errors = json_decode($validate->errors());
        foreach ($errors as $list) {
            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $list[0],
                ], 200);
            }
        }

        $country_id = $request->country_id;
        $states= state()->where('country_id',$country_id)->orderBy('name', 'ASC')->get()->toArray();
        if (!empty($states) && count($states) > 0) {
            return response()->json(['success' => true, 'message' => 'State List', 'data' => $states], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No State Found', ], 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'state_id' => 'required',
        ]);
        $errors = json_decode($validate->errors());
        foreach ($errors as $list) {
            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $list[0],
                ], 200);
            }
        }

        $state_id = $request->state_id;
        $cities= city()->where('state_id',$state_id)->orderBy('name', 'ASC')->get()->toArray();
        if (!empty($cities) && count($cities) > 0) {
            return response()->json(['success' => true, 'message' => 'City List', 'data' => $cities], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No City Found', ], 200);
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
            return response()->json(['success' => false, 'message' => 'No Stream Found', ], 200);
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

                ], 200);
            }
        }

        $stream_uuid = $request->stream_uuid;
        $streams = subjects()->where('stream_uuid', $stream_uuid)->orderBy('title', 'ASC')->get()->toArray();
        if (!empty($streams) && count($streams) > 0) {
            return response()->json(['success' => true, 'message' => 'Subject List', 'data' => $streams], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No Subjects Found', ], 200);
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

                ], 200);
            }
        }

        $email = $request->email;
        $password = $request->password;
        $credentials = ['email' => $email, 'password' => $password];
        try {
            if(user()->where('email',$email)->count() > 0){
                $userDetail = user()->where('email',$email)->first();
                if(!$userDetail->status){
                    return response()->json(['success' => false, 'message' => trans('api.account_deactivated')]);

                }
                if(!in_array($userDetail->role->title,checkRoles())){
                    return response()->json(['success' => false, 'message' => trans('api.do_not_have_access')]);
                }
                $token = JWTAuth::attempt($credentials);
                if (!$token) {
                    return response()->json(['success' => false, 'message' => 'invalid_credentials'], 200);
                }
                $data = user()->with('role', 'country_detail', 'state_detail', 'city_detail','professor_subjects.subject')->where('email', $email)->first()->toArray();
                $data['token'] = $token;
                $data['image'] = $this->base_url . $data['image'];
                return response()->json(['success' => true, 'message' => 'Login Successful', 'data' => $data], 200);
            }else{
                return response()->json(['success' => false, 'message' => 'Email not found'], 200);
            }
      } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'could_not_create_token'], 200);
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

            ], 200);
        }
        return response()->json($this->apiService->resendOtp($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request){
        $validation = $this->apiService->forgotPasswordValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->forgotPassword($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyForgotPassword(Request $request){
        $validation = $this->apiService->verifyForgotPasswordValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->verifyForgotPassword($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyChangePassword(Request $request){
        $validation = $this->apiService->changePasswordValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->changePassword($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUserProfile(Request  $request){
        $validation = $this->apiService->editUserProfileListValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->editUserProfile($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserProfile(Request  $request){
        $validation = $this->apiService->updateUserProfileListValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->updateUserProfile($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function moderatorPosts(Request $request){
        $validation = $this->apiService->moderatorPostsValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->moderatorPosts($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function studentPackageList(Request $request){
        $validation = $this->apiService->studentPackageListValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->studentPackageList($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPackageList(Request $request){
        $validation = $this->apiService->getAllPackageListValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->getAllPackageList($request));
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userUploadNotes(Request $request){
        $validation = $this->apiService->userUploadNotesValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->userUploadNotes($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userGetNotes(Request  $request){
        $validation = $this->apiService->userGetNotesValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->userGetNotes($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentList(Request  $request){
        $validation = $this->apiService->getStudentListValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->getStudentList($request));
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfessorList(Request  $request){
        $validation = $this->apiService->getProfessorListValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->getProfessorList($request));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userChangePassword(Request  $request){
        $validation = $this->apiService->userChangePasswordValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->userChangePassword($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNotes(Request  $request){
        $validation = $this->apiService->getUserNotesValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->getUserNotes($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUserNotes(Request  $request){
        $validation = $this->apiService->deleteUserNotesValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->deleteUserNotes($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userEditNotes(Request  $request){
        $validation = $this->apiService->editUserNotesValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->editUserNotes($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function studentPurchasedSubjects(Request  $request)
    {
        $validation = $this->apiService->studentPurchasedSubjectsValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->studentPurchasedSubjects($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addReview(Request  $request)
    {
        $validation = $this->apiService->addReviewValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->addReview($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubmittedReviews(Request  $request)
    {
        $validation = $this->apiService->getSubmittedReviewsValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->getSubmittedReviews($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postQuery(Request  $request)
    {
        $validation = $this->apiService->postQueryValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->postQuery($request));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userGetQuery(Request  $request)
    {
        $validation = $this->apiService->userGetQueryValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->userGetQuery($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function professorReplyQuery(Request  $request)
    {
        $validation = $this->apiService->professorReplyQueryValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->professorReplyQuery($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNoteFile(Request  $request)
    {
        $validation = $this->apiService->deleteNoteFileValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->deleteNoteFile($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchasePackage(Request  $request)
    {
        $validation = $this->apiService->purchasePackageValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->purchasePackage($request));
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNoteDetail(Request $request){
        $validation = $this->apiService->getNoteDetailValidationRules($request);
        if (!$validation['success']) {
            return response()->json([
                'success' => $validation['success'],
                'message' => $validation['message'],

            ], 200);
        }
        return response()->json($this->apiService->getNoteDetail($request));
    }





}

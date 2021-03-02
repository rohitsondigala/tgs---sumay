<?php

namespace App\Services;


use App\Http\Resources\AllPackageListResource;
use App\Http\Resources\GetModeratorPostsResource;
use App\Http\Resources\GetProfessorListResource;
use App\Http\Resources\GetStudentListResource;
use App\Http\Resources\GetStudentProfessorNotesResource;
use App\Http\Resources\ProfessorGetProfileResource;
use App\Http\Resources\ProfessorGetQueryResource;
use App\Http\Resources\ProfessorGetReviewsResource;
use App\Http\Resources\ProfessorPurchasedSubjectsResource;
use App\Http\Resources\StudentGetProfileResource;
use App\Http\Resources\StudentGetQueryResource;
use App\Http\Resources\StudentGetReviewsResource;
use App\Http\Resources\StudentPackageListResource;
use App\Http\Resources\StudentPurchasedSubjectsResource;
use App\Http\Resources\UserGetNoteDetailResource;
use App\Http\Resources\UserGetNotesResource;
use App\Mail\ForgotPasswordOtp;
use App\Mail\ProfessorRegistraionOtp;
use App\Mail\StudentRegistraionOtp;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class ApiService
 * @package App\Services
 */
class ApiService
{
    /**
     * @param $validate
     * @return array
     */
    function validationResponse($validate)
    {
        $errors = json_decode($validate->errors());
        if (!empty($errors)) {
            foreach ($errors as $list) {
                if ($validate->fails()) {
                    return [
                        'success' => false,
                        'message' => $list[0],
                        'data' => array()
                    ];
                } else {
                    return [
                        'success' => true,
                        'message' => $list[0],
                        'data' => array()
                    ];
                }
            }
        } else {
            return [
                'success' => true,
                'message' => 'No Errors',
                'data' => array()
            ];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function studentRegisterValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'password' => 'required',
            'university_name' => 'required',
            'college_name' => 'required',
            'preferred_language' => 'required',
            'other_information' => 'required',
            'stream_uuid' => 'required',
            'subjects' => 'required',
            'access_token' => 'required'
        ]);
        return $this->validationResponse($validate);

    }

    /**
     * @param $request
     * @return array
     */
    function studentRegistration($request)
    {

        if (user()->where('email', $request->email)->count() > 0) {
            $user = user()->where('email', $request->email)->first();
            if ($user->verify) {
                return ['success' => false, 'message' => trans('api.email_exists')];
            }
            if (user_otp()->where('user_uuid', $user->uuid)->where('status', 1)->count() > 0) {
                return ['success' => false, 'message' => trans('api.email_exists')];
            } else {
                purchased_packages()->where('user_uuid', $user->uuid)->delete();
                student_details()->where('user_uuid', $user->uuid)->delete();
                return $this->studentCreate($user, $request);
            }
        } else {
            $userArray = $request->only('name', 'email', 'mobile', 'country', 'state', 'city', 'stream_uuid');
            if (!empty($request->image)) {
                $filePath = uploadMedia($request->image, 'profile');
                $userArray['image'] = $filePath;
            }
            $userArray['password'] = bcrypt($request->password);
            $userArray['role_uuid'] = student_role_uuid();
            $user = user()->create($userArray);
            return $this->studentCreate($user, $request);
        }

    }

    /**
     * @param $user
     * @param $request
     * @return array
     */
    function studentCreate($user, $request)
    {
        $studentDetails = $request->only('university_name', 'college_name', 'other_information', 'preferred_language');
        $subjects = $request->subjects;
        if ($user) {
            $user_uuid = $user->uuid;
            $studentDetails['user_uuid'] = $user_uuid;
            $otp = rand(111111, 999999);
            $userOtpArray = array(
                'user_uuid' => $user_uuid,
                'otp' => $otp,
                'status' => 0,
                'attempt' => 1
            );
            $subjects = explode(',', $subjects);
            foreach ($subjects as $list) {
                purchased_packages()->create(['user_uuid' => $user_uuid, 'stream_uuid' => $request->stream_uuid, 'subject_uuid' => $list, 'registration' => 1]);
            }
            student_details()->create($studentDetails);
            if (user_otp()->where('user_uuid', $user_uuid)->count() > 0) {
                $userOtpDetail = user_otp()->where('user_uuid', $user_uuid)->first();
                $currentDate = Carbon::parse($userOtpDetail->updated_at)->format('d-m-y');
                $todayDate = Carbon::now()->format('d-m-y');
                if ($currentDate != $todayDate) {
                    user_otp()->where('user_uuid', $user_uuid)->update(['attempt' => 0]);
                }
                if ($userOtpDetail->attempt >= 3 && $currentDate == $todayDate) {
                    return ['success' => false, 'message' => trans('api.maximum_attempt')];
                } else {
                    user_otp()->where('user_uuid', $user_uuid)->increment('attempt');
                }
            } else {
                user_otp()->create($userOtpArray);
            }
            $user = user_otp()->where('user_uuid', $user_uuid)->first();
            Mail::to($request->email)->send(new StudentRegistraionOtp($user));
            return ['success' => true, 'message' => trans('api.user_registration_otp_sent')];
        } else {
            return ['success' => false, 'message' => trans('api.fail')];
        }
    }


    /**
     * @param $request
     * @return array
     */
    function professorRegisterValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'password' => 'required',
            'university_name' => 'required',
            'education_qualification' => 'required',
            'research_of_expertise' => 'required',
            'achievements' => 'required',
            'college_name' => 'required',
            'preferred_language' => 'required',
            'other_information' => 'required',
            'stream_uuid' => 'required',
            'subjects' => 'required',
            'access_token' => 'required'
        ]);
        return $this->validationResponse($validate);

    }

    /**
     * @param $request
     * @return array
     */
    function professorRegistration($request)
    {
        if (user()->where('email', $request->email)->count() > 0) {
            $user = user()->where('email', $request->email)->first();
            if ($user->verify) {
                return ['success' => false, 'message' => trans('api.email_exists')];
            }
            if (user_otp()->where('user_uuid', $user->uuid)->where('status', 1)->count() > 0) {
                return ['success' => false, 'message' => trans('api.email_exists')];
            } else {
                professor_subjects()->where('user_uuid', $user->uuid)->delete();
                professor_details()->where('user_uuid', $user->uuid)->delete();
                return $this->professorCreate($user, $request);
            }
        } else {
            $userArray = $request->only('name', 'email', 'mobile', 'country', 'state', 'city', 'stream_uuid');
            $userArray['role_uuid'] = professor_role_uuid();
            $userArray['password'] = bcrypt($request->password);
            if (!empty($request->image)) {
                $filePath = uploadMedia($request->image, 'profile');
                $userArray['image'] = $filePath;
            }
            $user = user()->create($userArray);
            return $this->professorCreate($user, $request);
        }

    }

    /**
     * @param $user
     * @param $request
     * @return array
     */
    function professorCreate($user, $request)
    {
        $professorDetails = $request->only('education_qualification', 'research_of_expertise', 'achievements', 'university_name', 'college_name', 'other_information', 'preferred_language');
        $subjects = $request->subjects;
        if ($user) {
            $user_uuid = $user->uuid;
            $professorDetails['user_uuid'] = $user_uuid;
            $otp = rand(111111, 999999);
            $userOtpArray = array(
                'user_uuid' => $user_uuid,
                'otp' => $otp,
                'status' => 0,
                'attempt' => 1
            );
            $subjects = explode(',', $subjects);
            foreach ($subjects as $list) {
                professor_subjects()->create(['user_uuid' => $user_uuid, 'stream_uuid' => $request->stream_uuid, 'subject_uuid' => $list]);
            }
            professor_details()->create($professorDetails);
            if (user_otp()->where('user_uuid', $user_uuid)->count() > 0) {
                $userOtpDetail = user_otp()->where('user_uuid', $user_uuid)->first();
                $currentDate = Carbon::parse($userOtpDetail->updated_at)->format('d-m-y');
                $todayDate = Carbon::now()->format('d-m-y');
                if ($currentDate != $todayDate) {
                    user_otp()->where('user_uuid', $user_uuid)->update(['attempt' => 0]);
                }
                if ($userOtpDetail->attempt >= 3 && $currentDate == $todayDate) {
                    return ['success' => false, 'message' => trans('api.maximum_attempt')];
                } else {
                    user_otp()->where('user_uuid', $user_uuid)->increment('attempt');
                }
            } else {
                user_otp()->create($userOtpArray);
            }
            $user = user_otp()->where('user_uuid', $user_uuid)->first();
            Mail::to($request->email)->send(new ProfessorRegistraionOtp($user));
            return ['success' => true, 'message' => trans('api.user_registration_otp_sent')];
        } else {
            return ['success' => false, 'message' => trans('api.fail')];
        }
    }


    /**
     * @param $request
     * @return array
     */
    function verifyEmailValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'otp' => 'required|digits:6'
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function verifyEmail($request)
    {
        $email = $request->email;
        $otp = $request->otp;
        if (user()->where('email', $request->email)->count() > 0) {
            $user_uuid = user()->where('email', $email)->value('uuid');
            $userDetail = user()->where('email', $email)->first();
            if (!in_array($userDetail->role->title, checkRoles())) {
                return ['success' => false, 'message' => trans('api.do_not_have_access')];
            }
            if (user_otp()->where('user_uuid', $user_uuid)->where('status', 1)->count() > 0) {
                return ['success' => false, 'message' => trans('api.already_verified')];
            }
            if (user_otp()->where('user_uuid', $user_uuid)->where('otp', $otp)->count() > 0) {
                $update = user_otp()->where('user_uuid', $user_uuid)->where('otp', $otp)->update(['status' => 1]);
                if ($update) {
                    user()->where('uuid', $user_uuid)->update(['verify' => 1]);
                    user_otp()->where('user_uuid', $user_uuid)->delete();
                    return ['success' => true, 'message' => trans('api.otp_verified')];
                } else {
                    return ['success' => false, 'message' => trans('api.server_issue')];
                }
            } else {
                return ['success' => false, 'message' => trans('api.incorrect_otp')];
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function resendOtpValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function resendOtp($request)
    {
        $email = $request->email;
        if (user()->where('email', $request->email)->count() > 0) {
            $user_uuid = user()->where('email', $email)->value('uuid');
            $userDetail = user()->where('email', $email)->first();
            if (!in_array($userDetail->role->title, checkRoles())) {
                return ['success' => false, 'message' => trans('api.do_not_have_access')];
            }
            if ($user_uuid) {
                if (user_otp()->where('user_uuid', $user_uuid)->count() <= 0) {
                    return ['success' => false, 'message' => trans('api.no_record_to_resend')];
                }
                if (user_otp()->where('user_uuid', $user_uuid)->where('status', 1)->count() > 0) {
                    return ['success' => false, 'message' => trans('api.already_verified')];
                }
                $userOtpDetail = user_otp()->where('user_uuid', $user_uuid)->first();
                $currentDate = Carbon::parse($userOtpDetail->updated_at)->format('d-m-y');
                $todayDate = Carbon::now()->format('d-m-y');
                if ($currentDate != $todayDate) {
                    user_otp()->where('user_uuid', $user_uuid)->update(['attempt' => 0]);
                }
                if ($userOtpDetail->attempt >= 3 && $currentDate == $todayDate) {
                    return ['success' => false, 'message' => trans('api.maximum_attempt')];
                } else {
                    $userOtpDetail->increment('attempt');
                    if ($request->type == 'forgot-password') {
                        Mail::to($userOtpDetail->user->email)->send(new ForgotPasswordOtp($userOtpDetail));
                    } else {
                        if ($request->user == 'student') {
                            Mail::to($userOtpDetail->user->email)->send(new StudentRegistraionOtp($userOtpDetail));
                        } else {
                            Mail::to($userOtpDetail->user->email)->send(new ProfessorRegistraionOtp($userOtpDetail));
                        }
                    }
                    return ['success' => true, 'message' => trans('api.resend_otp')];
                }
            } else {
                return ['success' => false, 'message' => trans('api.server_issue')];
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function forgotPasswordValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function forgotPassword($request)
    {
        $email = $request->email;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            if (!in_array($userDetail->role->title, checkRoles())) {
                return ['success' => false, 'message' => trans('api.do_not_have_access')];
            }
            $user_uuid = $userDetail->uuid;
            $otp = rand(111111, 999999);

            $userOtpArray = array(
                'user_uuid' => $user_uuid,
                'otp' => $otp,
                'status' => 0,
                'attempt' => 1
            );
            if (user_otp()->where('user_uuid', $user_uuid)->where('status', 0)->count() > 0) {
                $userOtpDetail = user_otp()->where('user_uuid', $user_uuid)->where('status', 0)->first();
                $currentDate = Carbon::parse($userOtpDetail->updated_at)->format('d-m-y');
                $todayDate = Carbon::now()->format('d-m-y');
                if ($currentDate != $todayDate) {
                    user_otp()->where('user_uuid', $user_uuid)->update(['attempt' => 0]);
                }
                if ($userOtpDetail->attempt >= 3 && $currentDate == $todayDate) {
                    return ['success' => false, 'message' => trans('auth.maximum_attempt')];
                } else {

                    Mail::to($userOtpDetail->user->email)->send(new ForgotPasswordOtp($userOtpDetail));
                    user_otp()->where('user_uuid', $user_uuid)->increment('attempt');
                    user()->where('uuid', $user_uuid)->update(['verify' => 0]);
                    return ['success' => true, 'message' => trans('auth.forgot_password')];
                }
            } else {
                $userOtpDetail = user_otp()->create($userOtpArray);
                if ($userOtpDetail) {
                    user()->where('uuid', $user_uuid)->update(['verify' => 0]);
                    Mail::to($userOtpDetail->user->email)->send(new ForgotPasswordOtp($userOtpDetail));
                    return ['success' => true, 'message' => trans('auth.forgot_password')];
                } else {
                    return ['success' => false, 'message' => trans('auth.server_issue')];
                }
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function verifyForgotPasswordValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'otp' => 'required|digits:6'
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function verifyForgotPassword($request)
    {
        $email = $request->email;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            if (!in_array($userDetail->role->title, checkRoles())) {
                return ['success' => false, 'message' => trans('api.do_not_have_access')];
            }
            $user_uuid = $userDetail->uuid;
            $otp = $request->otp;
            if (user_otp()->where('user_uuid', $user_uuid)->where('otp', $otp)->count() > 0) {
                $update = user_otp()->where('user_uuid', $user_uuid)->where('otp', $otp)->update(['status' => 1]);
                if ($update) {
                    user_otp()->where('user_uuid', $user_uuid)->delete();
                    return ['success' => true, 'message' => trans('api.otp_verified')];
                } else {
                    return ['success' => false, 'message' => trans('api.server_issue')];
                }
            } else {
                return ['success' => false, 'message' => trans('api.incorrect_otp')];
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function changePasswordValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function changePassword($request)
    {
        $email = $request->email;
        $password = $request->password;
        if (user()->where('email', $email)->count() > 0) {
            $userDetail = user()->where('email', $email)->first();
            if (!in_array($userDetail->role->title, checkRoles())) {
                return ['success' => false, 'message' => trans('api.do_not_have_access')];
            }
            $user_uuid = $userDetail->uuid;
            if (user_otp()->where('user_uuid', $user_uuid)->where('status', 0)->count() <= 0) {
                if (!$userDetail->verify) {
                    if (user()->where('email', $email)->update(['password' => bcrypt($password)])) {
                        user()->where('email', $email)->update(['verify' => 1]);
                        return ['success' => true, 'message' => trans('api.password_updated')];
                    } else {
                        return ['success' => false, 'message' => trans('api.server_issue')];
                    }
                } else {
                    return ['success' => false, 'message' => trans('api.otp_not_verified')];
                }
            } else {
                return ['success' => false, 'message' => trans('api.otp_not_verified')];
            }
        } else {
            return ['success' => false, 'message' => trans('api.email_not_found')];
        }
    }


    /**
     * @param $request
     * @return array
     */
    function moderatorPostsValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function moderatorPosts($request)
    {
        $userDetail = getUserDetail($request->user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }

        $subjectList = getSubjectListUUID($userDetail);
        if (!$subjectList) {
            return ['success' => false, 'message' => trans('api.user_with_not_moderator_posts')];
        }

        $moderatorDailyPost = moderator_daily_posts()->with('moderator_subject.subject')->whereHas('moderator_subject', function ($query) use ($subjectList) {
            $query->whereIn('subject_uuid', $subjectList);
        })->orderBy('id', 'DESC')->get();

        if ($moderatorDailyPost->isNotEmpty()) {
            $returnData = GetModeratorPostsResource::collection($moderatorDailyPost)->toArray($request);
            return ['success' => true, 'message' => trans('api.moderator_posts'), 'data' => $returnData];
        } else {
            return ['success' => false, 'message' => trans('api.no_post_found')];
        }

    }

    /**
     * @param $request
     * @return array
     */
    function studentPackageListValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function studentPackageList($request)
    {
        $user_uuid = $request->user_uuid;
        $userDetail = getUserDetail($user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        $packageList = purchased_packages()->with('package', 'stream', 'subject')->where('user_uuid', $user_uuid)->where('is_purchased', 1)->get();
        if ($packageList->isEmpty()) {
            return ['success' => false, 'message' => trans('api.user_with_not_packages')];
        } else {
            $returnData = StudentPackageListResource::collection($packageList)->toArray($request);
            return ['success' => true, 'message' => trans('api.student_package_list'), 'data' => $returnData];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function getAllPackageListValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function getAllPackageList($request)
    {
        //TODO::Display only packages which are not subscribed
        $user_uuid = $request->user_uuid;
        $userDetail = getUserDetail($user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        $existingSubjects = getStudentSubjectsPurchased($userDetail);
        $allPackageList = packages()->with('stream', 'subject')->where('stream_uuid', $userDetail->stream_uuid)->whereNotIn('subject_uuid', $existingSubjects)->get();

        if ($allPackageList->isEmpty()) {
            return ['success' => false, 'message' => trans('api.user_with_not_packages')];
        } else {
            $returnData = AllPackageListResource::collection($allPackageList)->toArray($request);
            return ['success' => true, 'message' => trans('api.all_package_list'), 'data' => $returnData];
        }

    }


    /**
     * @param $request
     * @return array
     */
    function userUploadNotesValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
            'subject_uuid' => 'required|size:36',
            'title' => 'required',
            'description' => 'required',
            'image_files.*' => 'mimes:jpeg,png,jpg',
            'pdf_files.*' => 'sometimes|mimes:pdf',
            'audio_files.*' => 'sometimes|mimes:mp3',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function userUploadNotes($request)
    {
        $streamUUID = getStreamUUIDbySubjectUUID($request->subject_uuid);
        if (!$streamUUID) {
            return ['success' => false, 'message' => trans('api.subject_not_registered')];
        }
        $request->merge(['stream_uuid' => $streamUUID, 'slug' => Str::slug($request->title)]);
        $notesDetail = $request->except('_token', '_method', 'image_files', 'pdf_files', 'audio_files');

        $createNotes = notes()->create($notesDetail);
        if ($createNotes) {
            $notes_uuid = $createNotes->uuid;
            $image_files = $request->image_files;
            if (!empty($image_files)) {
                uploadNotesFiles($image_files, $notes_uuid, 'IMAGE');
            }
            $pdf_files = $request->pdf_files;
            if (!empty($pdf_files)) {
                uploadNotesFiles($pdf_files, $notes_uuid, 'PDF');
            }
            $audio_files = $request->audio_files;
            if (!empty($audio_files)) {
                uploadNotesFiles($audio_files, $notes_uuid, 'AUDIO');
            }
            sendNewNewNoteUploadNotification($createNotes);
            return ['success' => true, 'message' => trans('api.notes_uploaded')];
        } else {
            return ['success' => false, 'message' => trans('api.fail')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function userGetNotesValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    public function userGetNotes($request)
    {
        $userDetail = getUserDetail($request->user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        $role_type = $request->role_type;
        $subject_uuid = $request->subject_uuid;
        $existingSubjects = getStudentSubjects($userDetail);
        $allNotes = notes()
            ->ofApprove()
            ->where('user_uuid', '!=', $userDetail->uuid)
            ->whereIn('subject_uuid', $existingSubjects);

        if(!empty($role_type)){
            $allNotes->whereHas('user.role',function ($query) use ($role_type){
                $query->where('title',$role_type);
            });
        }

        if(!empty($subject_uuid)){
            $allNotes->where('subject_uuid',$subject_uuid);
        }

        $allNotes = $allNotes->ofOrderBy('DESC')->get();
        if ($allNotes->isNotEmpty()) {
            $returnData = UserGetNotesResource::collection($allNotes)->toArray($request);
            return ['success' => true, 'message' => trans('api.all_package_list'), 'data' => $returnData];
        } else {
            return ['success' => false, 'message' => trans('api.user_with_no_notes')];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function getStudentListValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }


    /**
     * @param $request
     * @return array
     */
    public function getStudentList($request)
    {
        $userDetail = getUserDetail($request->user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }

        $existingSubjects = getStudentSubjects($userDetail);
        $studentList = purchased_packages()
            ->where('user_uuid', '!=', $userDetail->uuid)
            ->whereIn('subject_uuid', $existingSubjects)
            ->orderBy('user_uuid')
            ->get();

        if ($studentList->isNotEmpty()) {
            $returnData = GetStudentListResource::collection($studentList)->toArray($request);
            return ['success' => true, 'message' => trans('api.get_student_list'), 'data' => $returnData];
        } else {
            return ['success' => false, 'message' => trans('api.no_student_to_related_subjects')];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function getProfessorListValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }


    /**
     * @param $request
     * @return array
     */
    public function getProfessorList($request)
    {
        $userDetail = getUserDetail($request->user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }

        $existingSubjects = getStudentSubjects($userDetail);

        $studentList = professor_subjects()
            ->where('user_uuid', '!=', $userDetail->uuid)
            ->whereIn('subject_uuid', $existingSubjects)
            ->orderBy('user_uuid')
            ->get();

        if ($studentList->isNotEmpty()) {
            $returnData = GetProfessorListResource::collection($studentList)->toArray($request);
            return ['success' => true, 'message' => trans('api.get_professor_list'), 'data' => $returnData];
        } else {
            return ['success' => false, 'message' => trans('api.no_student_to_related_subjects')];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function editUserProfileListValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return ProfessorGetProfileResource|StudentGetProfileResource|array
     */
    public function editUserProfile($request)
    {
        $user_uuid = $request->user_uuid;
        $userDetail = getUserDetail($user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        if ($userDetail->role->title == "STUDENT") {
            return (new StudentGetProfileResource($userDetail));
        } else {
            return (new ProfessorGetProfileResource($userDetail));
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function updateUserProfileListValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'university_name' => 'sometimes',
            'college_name' => 'sometimes',
            'preferred_language' => 'sometimes',
            'other_information' => 'sometimes',
            'access_token' => 'sometimes',
            'image' => 'sometimes',
            'research_of_expertise' => 'sometimes',
            'achievements' => 'sometimes',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     */
    public function updateUserProfile($request)
    {
        $user_uuid = $request->user_uuid;
        $userDetail = getUserDetail($user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        $userArray = [
            'name' => $request->name,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'access_token' => $request->access_token
        ];
        if (!empty($request->image)) {
            $filePath = uploadMedia($request->image, 'profile');
            $userArray['image'] = $filePath;
        }

        $userUpdate = user()->where('uuid', $user_uuid)->update($userArray);


        if ($userDetail->role->title == 'PROFESSOR') {
            $userDetailUpdate = professor_details()->where('user_uuid', $user_uuid)->update(
                $request->only(['university_name', 'college_name', 'preferred_language', 'other_information', 'achievements', 'research_of_expertise','education_qualification'])
            );
        } else {
            $userDetailUpdate = student_details()->where('user_uuid', $user_uuid)->update(
                $request->only(['university_name', 'college_name', 'preferred_language', 'other_information'])
            );
        }

        if ($userUpdate && $userDetailUpdate) {
            $userDetail = getUserDetail($user_uuid);
            if (!empty($userDetail->image)) {
                $image = env('APP_URL') . $userDetail->image;
            } else {
                $image = null;
            }
            return ['success' => true, 'message' => trans('api.user_updated'), 'full_image_path' => $image];
        } else {
            return ['success' => false, 'message' => trans('api.fail_to_update')];
        }


    }

    /**
     * @param $request
     * @return array
     */
    function userChangePasswordValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required',
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function userChangePassword($request)
    {
        $user_uuid = $request->user_uuid;
        $userDetail = getUserDetail($user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        $current_password = $request->old_password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;
        if (Hash::check($current_password, $userDetail->password)) {
            if ($current_password == $new_password) {
                return ['success' => false, 'message' => trans('auth.old_new_same')];
            } elseif ($new_password == $confirm_password) {
                user()->where('uuid', $user_uuid)->update(['password' => bcrypt($confirm_password)]);
                return ['success' => true, 'message' => trans('api.password_updated')];
            } else {
                return ['success' => false, 'message' => trans('api.password_not_match')];
            }
        } else {
            return ['success' => false, 'message' => trans('api.current_password_does_not_match')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function getUserNotesValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return GetStudentProfessorNotesResource|array
     */
    public function getUserNotes($request)
    {
        $userDetail = getUserDetail($request->user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        $allNotes = notes()->where('user_uuid', $userDetail->uuid)->ofOrderBy('DESC')->get();
        if ($allNotes->isNotEmpty()) {
            $returnData = GetStudentProfessorNotesResource::collection($allNotes)->toArray($request);
            return ['success' => true, 'message' => trans('api.user_uploaded_notes'), 'data' => $returnData];
        } else {
            return ['success' => false, 'message' => trans('api.user_with_no_notes')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function deleteUserNotesValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'note_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return GetStudentProfessorNotesResource|array
     */
    public function deleteUserNotes($request)
    {
        $note_uuid = $request->note_uuid;
        if (notes()->where('uuid', $note_uuid)->count() > 0) {
            $noteDetail = notes()->where('uuid', $note_uuid)->first();
            if ($noteDetail->delete()) {
                return ['success' => true, 'message' => trans('api.user_note_deleted')];
            } else {
                return ['success' => false, 'message' => trans('api.fail')];
            }
        } else {
            return ['success' => false, 'message' => trans('api.note_not_found')];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function editUserNotesValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
            'note_uuid' => 'required|size:36',
            'title' => 'required',
            'description' => 'required',
            'image_files.*' => 'mimes:jpeg,png,jpg',
            'pdf_files.*' => 'sometimes|mimes:pdf',
            'audio_files.*' => 'sometimes|mimes:mp3',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return GetStudentProfessorNotesResource|array
     */
    public function editUserNotes($request)
    {
        $note_uuid = $request->note_uuid;
        $user_uuid = $request->user_uuid;
        if (notes()->where('uuid', $note_uuid)->where('user_uuid', $user_uuid)->count() > 0) {
            $notesArray = $request->except('_token', '_method', 'image_files', 'pdf_files', 'audio_files');
            $noteDetail = notes()->where('uuid', $note_uuid)->first();
            $notesArray['approve'] = 4;
            $updateNotes = $noteDetail->update($notesArray);
            $noteDetail->increment('edited_count');
            if ($updateNotes) {
                $notes_uuid = $noteDetail->uuid;

                $image_files = $request->image_files;
                if (!empty($image_files)) {
                    uploadNotesFiles($image_files, $notes_uuid, 'IMAGE');
                    delete_audio_files($noteDetail);
                    delete_pdf_files($noteDetail);
                }
                $pdf_files = $request->pdf_files;
                if (!empty($pdf_files)) {
                    uploadNotesFiles($pdf_files, $notes_uuid, 'PDF');
                    delete_audio_files($noteDetail);
                    delete_image_files($noteDetail);
                }
                $audio_files = $request->audio_files;
                if (!empty($audio_files)) {
                    uploadNotesFiles($audio_files, $notes_uuid, 'AUDIO');
                    delete_image_files($noteDetail);
                    delete_pdf_files($noteDetail);
                }
                sendEditNotesUpdateNotification($noteDetail);
                return ['success' => true, 'message' => trans('api.notes_edited')];
            } else {
                return ['success' => false, 'message' => trans('api.fail')];
            }
        } else {
            return ['success' => false, 'message' => trans('api.note_not_found')];
        }

    }

    /**
     * @param $request
     * @return array
     */
    public function studentPurchasedSubjectsValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function studentPurchasedSubjects($request)
    {
        $user_uuid = $request->user_uuid;
        $userDetail = getUserDetail($user_uuid);
        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        if($userDetail->role->title == 'PROFESSOR'){
            $packageList = professor_subjects()->where('user_uuid',$user_uuid)->get();
        }else{
            $packageList = purchased_packages()->where('user_uuid', $user_uuid)->where('is_purchased', 1)->get();
        }

        if ($packageList->isEmpty()) {
            return ['success' => false, 'message' => trans('api.user_with_not_packages')];
        } else {
            if($userDetail->role->title == 'PROFESSOR') {
                $returnData = ProfessorPurchasedSubjectsResource::collection($packageList)->toArray($request);
            }else{
                $returnData = StudentPurchasedSubjectsResource::collection($packageList)->toArray($request);
            }
            return ['success' => true, 'message' => trans('api.student_purchased_subjects'), 'data' => $returnData];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function addReviewValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'from_user_uuid' => 'required|size:36',
            'to_user_uuid' => 'required|size:36',
            'subject_uuid' => 'required|size:36',
            'rating' => 'required',
            'description' => 'required',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function addReview($request)
    {
        $subject_uuid = $request->subject_uuid;
        $from_user_uuid = $request->from_user_uuid;
        $fromUserDetail = getUserDetail($from_user_uuid);

        $to_user_uuid = $request->to_user_uuid;
        $toUserDetail = getUserDetail($to_user_uuid);

        if (!$fromUserDetail && !$toUserDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }

        if (subjects()->where('uuid', $subject_uuid)->count() <= 0) {
            return ['success' => false, 'message' => trans('api.subject_not_found')];
        }

        if (reviews()->create($request->except(['_token', '_method']))) {
            return ['success' => true, 'message' => trans('api.review_added')];
        } else {
            return ['success' => false, 'message' => trans('api.fail')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function getSubmittedReviewsValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'from_user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }


    /**
     * @param $request
     * @return array
     */
    function getSubmittedReviews($request)
    {
        $from_user_uuid = $request->from_user_uuid;
        $fromUserDetail = getUserDetail($from_user_uuid);

        if (!$fromUserDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }

        if ($fromUserDetail->role->title == "STUDENT") {
            $reviews = reviews()->where('from_user_uuid', $from_user_uuid)->orderBy('id', 'DESC')->get();
        } else {
            $reviews = reviews()->where('to_user_uuid', $from_user_uuid)->orderBy('id', 'DESC')->get();
        }
        if ($reviews->isEmpty()) {
            return ['success' => false, 'message' => trans('api.no_review_found')];
        } else {
            if ($fromUserDetail->role->title == "STUDENT") {
                $returnData = StudentGetReviewsResource::collection($reviews)->toArray($request);
            } else {
                $returnData = ProfessorGetReviewsResource::collection($reviews)->toArray($request);
            }
            return ['success' => true, 'message' => trans('api.reviews'), 'data' => $returnData];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function postQueryValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'from_user_uuid' => 'required|size:36',
            'to_user_uuid' => 'required|size:36',
            'subject_uuid' => 'required|size:36',
            'title' => 'required',
            'description' => 'required',
            'image_files.*' => 'mimes:jpeg,png,jpg',
            'pdf_files.*' => 'sometimes|mimes:pdf',
            'audio_files.*' => 'sometimes|mimes:mp3',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function postQuery($request)
    {
        $streamUUID = getStreamUUIDbySubjectUUID($request->subject_uuid);
        if (!$streamUUID) {
            return ['success' => false, 'message' => trans('api.subject_not_registered')];
        }

        $request->merge(['stream_uuid' => $streamUUID, 'slug' => Str::slug($request->title)]);
        $notesDetail = $request->except('_token', '_method', 'image_files', 'pdf_files', 'audio_files');

        $createNotes = post_query()->create($notesDetail);
        if ($createNotes) {
            $notes_uuid = $createNotes->uuid;
            $image_files = $request->image_files;
            if (!empty($image_files)) {
                uploadPostQueryFiles($image_files, $notes_uuid, 'IMAGE');
            }
            $pdf_files = $request->pdf_files;
            if (!empty($pdf_files)) {
                uploadPostQueryFiles($pdf_files, $notes_uuid, 'PDF');
            }
            $audio_files = $request->audio_files;
            if (!empty($audio_files)) {
                uploadPostQueryFiles($audio_files, $notes_uuid, 'AUDIO');
            }
//            sendNewNewNoteUploadNotification($createNotes);
            return ['success' => true, 'message' => trans('api.query_uploaded')];
        } else {
            return ['success' => false, 'message' => trans('api.fail')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function userGetQueryValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'user_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function userGetQuery($request)
    {
        $user_uuid = $request->user_uuid;
        $userDetail = getUserDetail($user_uuid);

        if (!$userDetail) {
            return ['success' => false, 'message' => trans('api.user_not_found')];
        }
        if ($userDetail->role->title == 'STUDENT') {
            $reviews = post_query()->where('from_user_uuid', $user_uuid)->orderBy('id', 'DESC')->get();
        } else {
            $reviews = post_query()->where('to_user_uuid', $user_uuid)->orderBy('id', 'DESC')->get();
        }
        if ($reviews->isEmpty()) {
            return ['success' => false, 'message' => trans('api.no_queries_found')];
        } else {
            if ($userDetail->role->title == 'STUDENT') {
                $returnData = StudentGetQueryResource::collection($reviews)->toArray($request);
            } else {
                $returnData = ProfessorGetQueryResource::collection($reviews)->toArray($request);
            }
            return ['success' => true, 'message' => trans('api.queries'), 'data' => $returnData];
        }
    }

    /**
     * @param $request
     * @return array
     */
    function professorReplyQueryValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'post_query_uuid' => 'required|size:36',
            'description' => 'required',
            'image_files.*' => 'mimes:jpeg,png,jpg',
            'pdf_files.*' => 'sometimes|mimes:pdf',
            'audio_files.*' => 'sometimes|mimes:mp3',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function professorReplyQuery($request)
    {
        $post_query_uuid = $request->post_query_uuid;
        if (post_query()->where('uuid', $post_query_uuid)->count() <= 0) {
            return ['success' => false, 'message' => trans('api.posted_query_not_found')];
        } else {
            $postDetail = post_query()->where('uuid', $post_query_uuid)->first();
            if ($postDetail->post_reply()->count() > 0) {
                return ['success' => false, 'message' => trans('api.already_replied')];
            }
        }
        $notesDetail = $request->except('_token', '_method', 'image_files', 'pdf_files', 'audio_files');
        $postQueryReply = post_query_reply()->create($notesDetail);
        if ($postQueryReply) {
            $notes_uuid = $postQueryReply->uuid;
            $image_files = $request->image_files;
            if (!empty($image_files)) {
                uploadPostQueryReplyFiles($image_files, $notes_uuid, 'IMAGE');
            }
            $pdf_files = $request->pdf_files;
            if (!empty($pdf_files)) {
                uploadPostQueryReplyFiles($pdf_files, $notes_uuid, 'PDF');
            }
            $audio_files = $request->audio_files;
            if (!empty($audio_files)) {
                uploadPostQueryReplyFiles($audio_files, $notes_uuid, 'AUDIO');
            }
//            sendNewNewNoteUploadNotification($createNotes);
            return ['success' => true, 'message' => trans('api.replied')];
        } else {
            return ['success' => false, 'message' => trans('api.fail')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function deleteNoteFileValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'note_file_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function deleteNoteFile($request)
    {
        $note_file_uuid = $request->note_file_uuid;
        $noteDetail = notes_files()->where('uuid', $note_file_uuid)->first();

        if (!$noteDetail) {
            return ['success' => false, 'message' => trans('api.note_file_not_found')];
        }

        $noteFilePath = $noteDetail->file_path;
        if ($noteDetail->delete()) {
            if (File::exists(public_path($noteFilePath))) {
                File::delete(public_path($noteFilePath));
            }
            return ['success' => true, 'message' => trans('api.note_file_deleted')];
        } else {
            return ['success' => false, 'message' => trans('api.fail')];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function purchasePackageValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'package_uuid' => 'required|size:36',
            'user_uuid' => 'required|size:36',
            'subject_uuid' => 'required|size:36',
            'payment_id' => 'required',
            'duration_in_days' => 'required',
            'price' => 'required',
            'payment_status' => 'required',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function purchasePackage($request)
    {
        $payment_status = $request->payment_status;
        if($payment_status == 'FAIL'){
            return ['success' => false, 'message' => trans('api.payment_fail')];
        }

        $purchasedPackageDetail = purchased_packages()->where('user_uuid',$request->user_uuid)->where('subject_uuid',$request->subject_uuid)->first();

        if(!$purchasedPackageDetail){
            return ['success' => false, 'message' => trans('api.user_with_this_subject_not_found')];
        }else{
            if($purchasedPackageDetail->is_purchased == 1){
                return ['success' => false, 'message' => trans('api.already_purchased')];
            }
        }

        $performPurchase = storePurchasePackage($request,$purchasedPackageDetail);

        if ($performPurchase) {
                return ['success' => true, 'message' => trans('api.package_purchased')];
        } else {
                return ['success' => false, 'message' => trans('api.fail')];
        }
    }


    /**
     * @param $request
     * @return array
     */
    public function getNoteDetailValidationRules($request)
    {
        $validate = Validator::make($request->all(), [
            'note_uuid' => 'required|size:36',
        ]);
        return $this->validationResponse($validate);
    }

    /**
     * @param $request
     * @return array
     */
    function getNoteDetail($request)
    {
        $note_uuid = $request->note_uuid;
        $noteDetail = notes()->where('uuid',$note_uuid)->first();
        if(!$noteDetail){
            return ['success' => false, 'message' => trans('api.no_note_found')];
        }else{
            $returnData = new UserGetNoteDetailResource($noteDetail);
            return ['success' => true, 'message' => trans('api.note_detail'), 'data' => $returnData];
        }
    }

}

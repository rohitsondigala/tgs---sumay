<?php

namespace App\Services;


use App\Mail\ForgotPasswordOtp;
use App\Mail\ProfessorRegistraionOtp;
use App\Mail\StudentRegistraionOtp;
use Carbon\Carbon;
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
            'email' => 'required|email',
            'mobile' => 'required|numeric',
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
            'email' => 'required|email',
            'mobile' => 'required|numeric',
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
            $userArray = $request->only('name', 'email', 'mobile', 'country', 'state', 'city');
            $userArray['role_uuid'] = professor_role_uuid();
            $userArray['password'] = bcrypt($request->password);
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
            'email' => 'required|email',
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
            'email' => 'required|email',
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
            'email' => 'required|email',
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
            'email' => 'required|email',
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
            'email' => 'required|email',
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
            return ['success' => false, 'message' => trans('api.user_with_not_subjects')];
        }

        $moderatorDailyPost = moderator_daily_posts()->with('moderator_subject.subject')->whereHas('moderator_subject', function ($query) use ($subjectList) {
            $query->whereIn('subject_uuid', $subjectList);
        })->orderBy('id', 'DESC')->get()->toArray();

        if (!empty($moderatorDailyPost)) {
            return ['success' => true, 'message' => trans('api.moderator_posts'), 'data' => $moderatorDailyPost];
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
        $packageList = packages()->with('subject', 'stream', 'purchase_detail')
            ->whereHas('purchase_detail', function ($query) use ($user_uuid) {
                $query->where('user_uuid', $user_uuid);
            })->get()->toArray();
        if ($packageList) {
            return ['success' => true, 'message' => trans('api.student_subject_list'), 'data' => $packageList];
        } else {
            return ['success' => false, 'message' => trans('api.user_with_not_subjects')];
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
        $existingPackage = [];
        $allPackageList = packages()->with('stream', 'subject')->where('stream_uuid', $userDetail->stream_uuid)->whereNotIn('uuid', $existingPackage)->get()->toArray();
        if (!empty($allPackageList)) {
            return ['success' => true, 'message' => trans('api.all_package_list'), 'data' => $allPackageList];
        } else {
            return ['success' => false, 'message' => trans('api.user_with_not_subjects')];
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
            'image_files' => 'array',
            'image_files.*' => 'mimes:jpeg,png,jpg',
            'pdf_files' => 'array',
            'pdf_files.*' => 'sometimes|mimes:pdf',
            'audio_files' => 'array',
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


}

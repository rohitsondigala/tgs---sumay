<?php

use Illuminate\Contracts\Foundation\Application;


/**
 * @return Application|mixed
 */
function user(){
    return app(App\Models\User::class);
}

/**
 * @return Application|mixed
 */
function user_roles(){
    return app(App\Models\UsersRoles::class);
}

/**
 * @return Application|mixed
 */
function country(){
    return app(App\Models\Country::class);
}

/**
 * @return Application|mixed
 */
function state(){
    return app(App\Models\State::class);
}

/**
 * @return Application|mixed
 */
function city(){
    return app(App\Models\City::class);
}

/**
 * @return Application|mixed
 */
function streams(){
    return app(App\Models\Streams::class);
}

/**
 * @return Application|mixed
 */
function subjects(){
    return app(App\Models\Subjects::class);
}

/**
 * @return Application|mixed
 */
function log_activities(){
    return app(App\Models\LogActivities::class);
}

/**
 * @return Application|mixed
 */
function forgot_password(){
    return app(App\Models\ForgotPasswordOtp::class);
}

/**
 * @return Application|mixed
 */
function moderator_subjects(){
    return app(App\Models\ModeratorSubject::class);
}

/**
 * @return Application|mixed
 */
function student_details(){
    return app(App\Models\StudentDetails::class);
}

/**
 * @return Application|mixed
 */
function student_subjects(){
    return app(App\Models\StudentSubjects::class);
}
/**
 * @return Application|mixed
 */
function professor_details(){
    return app(App\Models\ProfessorDetails::class);
}

/**
 * @return Application|mixed
 */
function professor_subjects(){
    return app(App\Models\ProfessorSubjects::class);
}

/**
 * @return Application|mixed
 */
function user_otp(){
    return app(App\Models\UsersOtp::class);
}

/**
 * @return Application|mixed
 */
function moderator_daily_posts(){
    return app(App\Models\ModeratorDailyPost::class);
}


/**
 * @return Application|mixed
 */
function packages(){
    return app(App\Models\Packages::class);
}

/**
 * @return Application|mixed
 */
function purchased_packages(){
    return app(App\Models\PurchasedPackages::class);
}

/**
 * @return Application|mixed
 */
function notes(){
    return app(App\Models\Notes::class);
}


/**
 * @return Application|mixed
 */
function notes_files(){
    return app(App\Models\NotesFiles::class);
}


/**
 * @return Application|mixed
 */
function reviews(){
    return app(App\Models\Reviews::class);
}

/**
 * @return Application|mixed
 */
function post_query(){
    return app(App\Models\PostQuery::class);
}

/**
 * @return Application|mixed
 */
function post_query_files(){
    return app(App\Models\PostQueryFiles::class);
}

/**
 * @return Application|mixed
 */
function post_query_reply(){
    return app(App\Models\PostQueryReply::class);
}

/**
 * @return Application|mixed
 */
function post_query_reply_files(){
    return app(App\Models\PostQueryReplyFiles::class);
}

/**
 * @return Application|mixed
 */
function purchased_packages_payments()
{
    return app(App\Models\PurchasedPackagesPayments::class);
}

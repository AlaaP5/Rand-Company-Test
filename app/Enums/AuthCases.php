<?php

namespace App\Enums;

enum AuthCases: string
{
    // for Auth
    case Password = 'password';
    case Email = 'email';
    case Role = 'role';
    case Token = 'token';
    case Having = 'Having';
    case Date = 'date';
    case User = 'user';
    case Logout_success = 'Logout successful';
    case Login_success = 'Login successful';
    case Register_success = 'Code sent to your email';
    case verification_success = 'Your account has been confirmed';
    case verification_failed = 'your code is not correct';
    case Forbidden_message = 'يرجى تسجيل الكود المرسل على الايميل الخاص بك';
    case Failed = 'input data is incorrect';

    // for destination
    case Get_destinations_success = 'get destinations successfully';
}

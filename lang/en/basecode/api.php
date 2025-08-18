<?php

declare(strict_types=1);

return [
    'updated' => ':Entity has been updated successfully.',
    'deleted' => ':Entity has been deleted successfully.',
    'registered' => ':Entity Registered Successfully.',
    'logged_in' => ':Entity Logged in Successfully.',
    'invalid_token' => 'Invalid or expired token. Please authenticate and try again.',
    'inactive_account' => 'Your account is inactive. Please contact the admin.',
    'phone' => 'Please enter a valid mobile number.',
    'account_linked' => 'Account is already linked to another user.',
    'signed_out' => 'You are logged out successfully',
    'past_signed_out' => 'Your are logged out from other devices successfully.',
    'invalid_forgot_password_req' => 'User not found. please register yourself.',
    'no_request' => 'Please request a password reset first.',
    'invalid_otp' => 'Invalid OTP. Please try again.',
    'expired_token' => 'Token expired. Please request a new one.',
    'password_reset_mismatch' => 'We were unable to process your request at the moment. Please try again or initiate a new password reset.',
    'not_found' => ':entity not found.',
    'request_recorded' => 'Request has been recorded',
    'new_password_must_be_different' => 'New password is same as the current password. Please select different password.',
    'email_already_yours' => 'The email entered is already belongs to you.',
    'mobile_already_yours' => 'The mobile number entered is already belongs to you.',
    'otp' => [
        'expired' => 'Your OTP has been expired. please request a new OTP.',
        'not_matched' => 'Wrong OTP was provided. Please provide correct OTP.',
        'sent' => 'Enter the One-time Password (OTP) that was Sent to :over',
        'verified' => 'Your OTP has been verified successfully.',
        'timed_out' => 'SMS cannot send. Timout reached.',
        'something_went_wrong' => 'Something goes wrong while sending OTP.',
    ],
    'too_many_attempts' => 'Too many attempts. Please try again in :seconds seconds.',
];

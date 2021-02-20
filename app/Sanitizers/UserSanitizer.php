<?php

namespace App\Sanitizers;

class UserSanitizer extends BaseSanitizer
{
    /**
     * An array of sanitizer methods to be executed.
     *
     * @var array
     */
    protected $sanitizers = [
        'EmailNotificationEnabled',
    ];

    protected function sanitizeEmailNotificationEnabled()
    {
        $this->checkbox('email_notification_enabled');
    }
}

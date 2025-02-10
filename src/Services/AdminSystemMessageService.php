<?php

namespace Flippingbook\Services;

class AdminSystemMessageService
{
    /**
     * Sending a system message in the admin panel.
     *
     * @param string $text
     * @param string $type
     * @return void
     */
    public function setAdminSystemMessage($text = '', $type = 'success')
    {
        $messages = session('flippingbook.admin.messages', []);

        if (empty($messages[$type])) {
            $messages[$type] = [];
        }

        $messages[$type][] = $text;

        session(['flippingbook.admin.messages' => $messages]);
    }
}

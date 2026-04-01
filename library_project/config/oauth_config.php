<?php
/**
 * LibManage Elite OAuth 2.0 Configuration
 * 🏛️🔒✨ Paste your Client Credentials below
 */

return [
    'google' => [
        'client_id' => '350032180205-ipkc0e6qorrkl48uabi1q0s7fjel4d2k.apps.googleusercontent.com',
        // 'client_secret' => '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',
        'redirect_uri' => 'http://localhost/library_project/public/oauth_callback.php?provider=google',
        'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
        'token_url' => 'https://oauth2.googleapis.com/token',
        'userinfo_url' => 'https://www.googleapis.com/oauth2/v3/userinfo',
        'scope' => 'openid email profile'
    ],
    'github' => [
        'client_id' => 'PASTE_YOUR_GITHUB_CLIENT_ID_HERE',
        'client_secret' => 'PASTE_YOUR_GITHUB_CLIENT_SECRET_HERE',
        'redirect_uri' => 'http://localhost/library_project/public/oauth_callback.php?provider=github',
        'auth_url' => 'https://github.com/login/oauth/authorize',
        'token_url' => 'https://github.com/login/oauth/access_token',
        'userinfo_url' => 'https://api.github.com/user',
        'scope' => 'read:user user:email'
    ]
];

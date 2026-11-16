<?php

namespace Src;

use OpenApi\Attributes as OAT;

class Login
{
	private const ACCESS_TOKEN_LIFE = 180;
    private const REFRESH_TOKEN_LIFE = 240;

    private static function accessSecret(): string {
        $secret = getenv('DVWA_API_ACCESS_SECRET');
        if (!is_string($secret) || !preg_match('/\A[a-f0-9]{64}\z/i', $secret)) {
            throw new \RuntimeException('OAuth access secret is not configured securely.');
        }
        return $secret;
    }

    private static function refreshSecret(): string {
        $secret = getenv('DVWA_API_REFRESH_SECRET');
        if (!is_string($secret) || !preg_match('/\A[a-f0-9]{64}\z/i', $secret)) {
            throw new \RuntimeException('OAuth refresh secret is not configured securely.');
        }
        return $secret;
    }
	
	public static function create_token() {
		$now = time();
		$tokenObj = new Token();
		$token = json_encode (array (
			"access_token" => $tokenObj->create_token(self::accessSecret(), $now + self::ACCESS_TOKEN_LIFE),
			"refresh_token" => $tokenObj->create_token(self::refreshSecret(), $now + self::REFRESH_TOKEN_LIFE),
			"token_type" => "bearer",
			"expires_in" => self::ACCESS_TOKEN_LIFE)
		);
		return $token;
	}

	public static function check_access_token($token) {
		$tokenObj = new Token();
		$decrypted = $tokenObj->decrypt_token ($token);

		if ($decrypted === false) {
			return false;
		}
		if ($decrypted['secret'] == self::accessSecret() && $decrypted['expires'] > time()) {
			return true;
		}
		return false;
	}

	public static function check_refresh_token($token) {
		$tokenObj = new Token();
		$decrypted = $tokenObj->decrypt_token ($token);

		if ($decrypted['secret'] == self::refreshSecret() && $decrypted['expires'] > time()) {
			return true;
		}
		return false;
	}
}

<?php

if (!function_exists('generate_encryption_key')) {
    /**
     * Generates a secure encryption key using PBKDF2 with SHA-256.
     *
     * This function derives a cryptographic key from a given password and salt
     * using the PBKDF2 (Password-Based Key Derivation Function 2) algorithm with
     * SHA-256 as the hash function. It performs 100,000 iterations to enhance
     * security against brute-force attacks and produces a 256-bit (32 bytes) key.
     *
     * @param string $password The password to derive the encryption key from.
     * @param string $salt     A unique salt value to use for key derivation.
     * @return string          The derived encryption key as raw binary data.
     */
    function generate_encryption_key(string $password, string $salt): string
    {
        return hash_pbkdf2(
            'sha256', // Use SHA-256 for stronger security
            $password, // The password to derive the key from
            $salt, // A unique salt for each key generation
            100000, // Number of iterations for key stretching
            32, // Length of the derived key in bytes (32 bytes = 256 bits)
            true // Output as raw binary data
        );
    }
}

if (!function_exists('encrypt_with_password')) {
    /**
     * Encrypts data using a password and a unique salt.
     *
     * This function generates an encryption key from the provided password and salt,
     * then encrypts the given data using AES-256-CBC encryption.
     *
     * @param string $data    The data to encrypt.
     * @param string $password The password to derive the encryption key.
     * @return string         The encrypted data in base64 format.
     */
    function encrypt_with_password(string $data, string $password): string
    {
        $salt = bin2hex(random_bytes(16)); // Generate a unique salt
        $key = generate_encryption_key($password, $salt); // Derive the encryption key

        // Generate a random IV (Initialization Vector) for AES-256-CBC
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        // Encrypt the data using AES-256-CBC
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        // Combine the salt, IV, and encrypted data for storage
        return base64_encode($salt . ':' . bin2hex($iv) . ':' . $encryptedData);
    }
}

if (!function_exists('decrypt_with_password')) {
    /**
     * Decrypts data using a password and a unique salt.
     *
     * This function extracts the salt and IV from the encrypted data, derives the
     * encryption key using the provided password, and decrypts the data.
     *
     * @param string $encryptedData The encrypted data in base64 format.
     * @param string $password      The password to derive the encryption key.
     * @return string               The decrypted data.
     */
    function decrypt_with_password(string $encryptedData, string $password): string
    {
        list($salt, $ivHex, $ciphertext) = explode(':', base64_decode($encryptedData), 3);
        $iv = hex2bin($ivHex); // Convert IV from hex to binary
        $key = generate_encryption_key($password, $salt); // Derive the encryption key

        // Decrypt the data using AES-256-CBC
        return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    }
}

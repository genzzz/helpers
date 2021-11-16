<?php
use PHPUnit\Framework\TestCase;
use Larapress\Helpers\Encrypter;
use Larapress\Helpers\Str;

class EncrypterTest extends TestCase
{
    public function test_expect_exception_encrypter_if_cipher_is_AES_128_CBC()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.');

        $_ENV['APP_KEY'] = Encrypter::generateKey('AES-256-CBC');
        new Encrypter('AES-128-CBC');
    }

    public function test_expect_exception_encrypter_if_cipher_is_AES_256_CBC()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.');

        $_ENV['APP_KEY'] = Encrypter::generateKey('AES-128-CBC');
        new Encrypter();
    }

    public function test_supported_function()
    {
        $key16 = Encrypter::generateKey('AES-128-CBC');
        $key32 = Encrypter::generateKey('AES-256-CBC');

        $this->assertTrue(Encrypter::supported($key16, 'AES-128-CBC'));
        $this->assertTrue(Encrypter::supported($key32, 'AES-256-CBC'));

        $this->assertFalse(Encrypter::supported($key32, 'AES-128-CBC'));
        $this->assertFalse(Encrypter::supported($key16, 'AES-256-CBC'));
    }

    public function test_return_encryptor_with_AES_256_CBC()
    {
        $_ENV['APP_KEY'] = Encrypter::generateKey('AES-256-CBC');

        $encrypter = new Encrypter();
        $this->assertInstanceOf(Encrypter::class, $encrypter);

        $this->assertSame(env("APP_KEY"), $encrypter->getKey());

        return $encrypter;
    }

    public function test_return_encryptor_with_AES_128_CBC()
    {
        $_ENV['APP_KEY'] = Encrypter::generateKey('AES-128-CBC');

        $encrypter = new Encrypter('AES-128-CBC');
        $this->assertInstanceOf(Encrypter::class, $encrypter);

        $this->assertSame(env("APP_KEY"), $encrypter->getKey());

        return $encrypter;
    }

    /**
     * @depends test_return_encryptor_with_AES_256_CBC
     */
    public function test_encrypt_decrypt_function_with_AES_256_CBC(Encrypter $encrypter)
    {
        $string = 'test';
        $encryptedString = $encrypter->encrypt($string);
        $this->assertIsString($encryptedString);

        $decryptedString = $encrypter->decrypt($encryptedString);
        $this->assertSame($decryptedString, $string);
    }

    /**
     * @depends test_return_encryptor_with_AES_128_CBC
     */
    public function test_encrypt_decrypt_function_with_AES_128_CBC(Encrypter $encrypter)
    {
        $string = 'test';
        $encryptedString = $encrypter->encrypt($string);
        $this->assertIsString($encryptedString);

        $decryptedString = $encrypter->decrypt($encryptedString);
        $this->assertSame($decryptedString, $string);
    }

    /**
     * @depends test_return_encryptor_with_AES_256_CBC
     */
    public function test_encrypt_decrypt_function_expect_exception(Encrypter $encrypter)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The payload is invalid.');

        $string = 'test';
        $encryptedString = $encrypter->encrypt($string);

        $char = $encryptedString[0];
        $_char = Str::random(1);

        while($char == $_char)
            $_char = Str::random(1);

        $encryptedString[0] = $_char;

        $decryptedString = $encrypter->decrypt($encryptedString);
    }

    /**
     * @depends test_return_encryptor_with_AES_256_CBC
     */
    public function test_encrypt_decrypt_function_expect_exception_change_encrypted_string(Encrypter $encrypter)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The payload is invalid.');

        $decryptedString = $encrypter->decrypt('test');
    }

    /**
     * @depends test_return_encryptor_with_AES_256_CBC
     */
    public function test_decrypt_function_expect_exception_change_key(Encrypter $encrypter)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The MAC is invalid.');

        $string = 'test';
        $encryptedString = $encrypter->encrypt($string);

        $_ENV['APP_KEY'] = Encrypter::generateKey('AES-256-CBC');

        $newEncrypter = new Encrypter();

        $decryptedString = $newEncrypter->decrypt($encryptedString);
    }
}
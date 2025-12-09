from Crypto.Cipher import AES  # noqa

import config


class EncryptSettings:
    def __init__(self, salt_key):
        self.KEY = bytearray(salt_key, 'utf-8')
        self.IV = bytearray(salt_key, 'utf-8')

    def encrypt(self, plaintext):
        cipher = AES.new(self.KEY, AES.MODE_CBC, self.IV)
        ciphertext = cipher.encrypt(self._pad(plaintext))
        return ciphertext.hex()

    @staticmethod
    def _pad(plaintext):
        """Pad the plaintext to a multiple of 16 bytes."""
        plaintext = plaintext.encode('utf-8')
        padding_size = 16 - (len(plaintext) % 16)
        padding = bytes([padding_size]) * padding_size
        return plaintext + padding

    def decrypt(self, ciphertext):
        if not ciphertext:
            return ''
        if isinstance(ciphertext, str):
            ciphertext = bytearray.fromhex(ciphertext)
        cipher = AES.new(self.KEY, AES.MODE_CBC, self.IV)
        plaintext = self._unpad(cipher.decrypt(ciphertext))
        return plaintext.decode('utf8')

    @staticmethod
    def _unpad(plaintext):
        """Remove padding from the plaintext."""
        padding_size = plaintext[-1]
        return plaintext[:-padding_size]


if __name__ == "__main__":
    salt = config.SALT_KEY
    settings = EncryptSettings(salt)
    data = settings.encrypt("openbts")
    decrypt = settings.decrypt(data)
    print(decrypt)
    print(data)

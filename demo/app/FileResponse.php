<?php declare(strict_types=1);

namespace App;

use HttpSoft\Response\ResponseExtensionTrait;
use HttpSoft\Response\ResponseStatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

class FileResponse implements ResponseInterface, ResponseStatusCodeInterface
{
    use ResponseExtensionTrait;

    public function __construct(
        string $file,
        int $lifetime = 0,
        int $code = self::STATUS_OK,
        array $headers = [],
        string $protocol = '1.1',
        string $reasonPhrase = ''
    ) {
        $file = '../' . $file;

        if (is_file($file)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $contentType = finfo_file($finfo, $file);
            $content = file_get_contents($file);
            
            header_remove();

            $headers += [
                'Content-Type' => $contentType,
                'Content-length' => filesize($file),
                'Accept-Ranges' => 'bytes',
                'Content-Disposition' => 'inline',
                'Content-Transfer-Encoding' => 'binary',
            ];

            if ($lifetime > 0) {
                $headers['Cache-Control'] = 'private, max-age='.$lifetime;
            }
        } else {
            $code = self::STATUS_NOT_FOUND;
            $reasonPhrase = 'File not found';
            $content = '';
        }
        
        $this->init($code, $reasonPhrase, $headers, $this->createBody($content), $protocol);
    }
}

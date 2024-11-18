<?php

namespace app\controllers;

use app\models\User;
use core\Controller;
use core\Redirect;
use core\Response;
use Endroid\QrCode\Builder\Builder;

class QrCodeController extends Controller
{
    public function generate($id)
    {
        $user = User::find($id);

        if (!$user) Redirect::to('not-found');

        $fileName = 'qrcodes/' . $user['id'] . '.png';
        $filePath = __DIR__ . '/../../' . $fileName;

        // Ensure the qrcodes directory exists
        if (!is_dir(__DIR__ . '/../../qrcodes')) {
            mkdir(__DIR__ . '/../../qrcodes', 0777, true);
        }

        $qrCode = Builder::create()
            ->data($user['id'])  // Add relevant data
            ->size(300)
            ->margin(10)
            ->labelText($user['name'])
            ->build();

        $qrCode->saveToFile($filePath);

        User::update($id, [
            'qr_code' => $fileName
        ]);

        Response::json(['success' => true, 'message' => 'QR Code generated successfully', 'data' => ['file' => $fileName]]);
    }
}
